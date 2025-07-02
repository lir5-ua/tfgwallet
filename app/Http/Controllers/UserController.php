<?php
namespace App\Http\Controllers;
use App\Models\Mascota;
use App\Models\Recordatorio;
use App\Models\User;
use App\Enums\Especie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    // obliga a que solo usuarios autenticados puedan acceder a los métodos del controlador.
        public function __construct()
        {
            $this->middleware('auth')->except(['create', 'store']);
        }
    public function index(Request $request)
    {
       $query = User::query();
    if ($request->has('busqueda') && $request->busqueda !== '') {
            $query->where('name', 'like', '%' . $request->busqueda . '%');
        }
        $usuarios = $query->paginate(10)->appends($request->all());
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {

        if(auth()->user() && !auth()->user()->is_admin){
        return redirect('/mascotas');
        }else{
            return view('usuarios.create');
        }
    }
    public function toggleEmailNotification(Request $request)
    {
        $user = Auth::user();
        $notificarEmail = $request->boolean('notificar_email');
        $enviarAhora = false;
        // Si antes estaba desactivado y ahora se activa, enviamos el email
        if (!$user->notificar_email && $notificarEmail) {
            $enviarAhora = true;
        }
        $user->notificar_email = $notificarEmail;
        $user->save();

        if ($enviarAhora) {
            // Buscar recordatorios próximos (hoy, mañana, pasado mañana)
            $hoy = now()->toDateString();
            $manana = now()->addDay()->toDateString();
            $pasado = now()->addDays(2)->toDateString();

            $recordatoriosHoy = \App\Models\Recordatorio::whereHas('mascota', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('fecha', $hoy)->where('realizado', false)->get();

            $recordatoriosManana = \App\Models\Recordatorio::whereHas('mascota', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('fecha', $manana)->where('realizado', false)->get();

            $recordatoriosPasado = \App\Models\Recordatorio::whereHas('mascota', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('fecha', $pasado)->where('realizado', false)->get();

            if ($recordatoriosHoy->isNotEmpty() || $recordatoriosManana->isNotEmpty() || $recordatoriosPasado->isNotEmpty()) {
                \Mail::to($user->email)->send(new \App\Mail\RecordatoriosDiarios($user, $recordatoriosHoy, $recordatoriosManana, $recordatoriosPasado));
            }
        }
        return back()->with('success', 'Preferencia de notificación actualizada.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }
    public function show(User $usuario)
    {
        $user = auth()->user();
        if (empty($user->ultima_conexion)) {
            return redirect()->route('verification.notice')->withErrors(['verificacion' => 'Debes verificar tu correo para acceder a tu perfil.']);
        }
        $mascotas = $usuario->mascotas()->paginate(5);

        // 🔔 RECORDATORIOS - Optimizado con cache para el perfil
        $recordatorios = collect();
        if (!$user->silenciar_notificaciones_web) {
            $cacheKey = "recordatorios_profile_user_{$usuario->id}"; // Unique cache key for the profile
            $recordatorios = \Cache::remember($cacheKey, 300, function () use ($usuario) {
                return \App\Models\Recordatorio::whereHas('mascota', function ($q) use ($usuario) {
                        $q->where('user_id', $usuario->id);
                    })
                    ->where('realizado', false)
                    ->whereDate('fecha', '>=', now()->toDateString())
                    ->orderBy('fecha', 'asc')
                    ->with(['mascota.usuario'])
                    ->limit(5)
                    ->get();
            });
        }

        session(['return_to_after_update' => url()->current()]);

        $hoy = \Carbon\Carbon::today();
        $manana = now()->addDay()->toDateString();
        $pasado = now()->addDays(2)->toDateString();

        return view('usuarios.show', compact('usuario', 'mascotas', 'recordatorios', 'hoy', 'manana', 'pasado'));
    }

    public function edit(User $usuario)
    {
        session(['previous_url' => url()->previous()]);
        return view('usuarios.edit', compact('usuario'));
    }


    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|min:6|confirmed',
            'esAdmin' => 'nullable|in:on',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        if ($request->hasFile('foto')) {
            $rutaImagen = $request->file('foto')->store('users', 'public');
            $usuario->foto = $rutaImagen;
        }
        $usuario->is_admin = $request->has('esAdmin');
        $usuario->notificar_email = $request->has('notificar_email');
        $usuario->save();

        return redirect(session('previous_url', route('usuarios.show', $usuario)))
            ->with('success', 'Perfil actualizado.');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }
    public function mascotas()
    {
        return $this->hasMany(Mascota::class);
    }

    public function toggleWebNotifications(Request $request)
    {
        $user = Auth::user();
        $user->silenciar_notificaciones_web = !$user->silenciar_notificaciones_web;
        $user->save();
        return back()->with('success', $user->silenciar_notificaciones_web ? 'Notificaciones web silenciadas.' : 'Notificaciones web activadas.');
    }

}
