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
        $mascotas = $usuario->mascotas()->paginate(5);
        $user = auth()->user(); // Get the authenticated user

        // 🔔 RECORDATORIOS - Optimizado con cache para el perfil
        $cacheKey = "recordatorios_profile_user_{$usuario->id}"; // Unique cache key for the profile
        $recordatorios = Cache::remember($cacheKey, 300, function () use ($usuario) {
            $hoy = now()->toDateString();
            $manana = now()->addDay()->toDateString();
            $pasado = now()->addDays(2)->toDateString();

            return Recordatorio::whereIn('fecha', [$hoy, $manana, $pasado])
                ->whereHas('mascota', function ($q) use ($usuario) {
                    $q->where('user_id', $usuario->id);
                })
                ->where('realizado', false)
                ->orderBy('fecha', 'asc')
                ->with(['mascota.usuario'])
                ->get();
        });

        session(['return_to_after_update' => url()->current()]);

        // You might need these variables in your profile view as well,
        // depending on how you display the reminders.
        $hoy = Carbon::today();
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
        }else{
            $rutaImagen = null;
        }
        $usuario->foto = $rutaImagen;
        if ($request->password) {
            $usuario->password = Hash::make($request->password);
        }
    $usuario->is_admin = $request->has('esAdmin');

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

}
