@if ($errors->any())
    <div class="mb-4 font-medium text-sm text-red-600">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('password.update') }}" class="max-w-md mx-auto mt-8">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">
    <label for="password" class="block mb-2 text-xs font-bold text-slate-700">Nueva contraseña</label>
    <input type="password" name="password" id="password" required class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow mb-2" placeholder="Nueva contraseña">
    <label for="password_confirmation" class="block mb-2 text-xs font-bold text-slate-700">Confirmar contraseña</label>
    <input type="password" name="password_confirmation" id="password_confirmation" required class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow mb-2" placeholder="Confirma la contraseña">
    <button type="submit" class="inline-block w-full px-6 py-2 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-blue-600 to-cyan-400 border-0 rounded-lg cursor-pointer shadow-soft-md text-xs ease-soft-in tracking-tight-soft hover:scale-102 hover:shadow-soft-xs active:opacity-85">Restablecer contraseña</button>
</form> 