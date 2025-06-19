@if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 font-medium text-sm text-red-600">
        {{ $errors->first('email') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="max-w-md mx-auto mt-8">
    @csrf
    <label for="email" class="block mb-2 text-xs font-bold text-slate-700">Correo electrónico</label>
    <input type="email" name="email" id="email" required class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow mb-2" placeholder="Introduce tu correo electrónico">
    <button type="submit" class="inline-block w-full px-6 py-2 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-blue-600 to-cyan-400 border-0 rounded-lg cursor-pointer shadow-soft-md text-xs ease-soft-in tracking-tight-soft hover:scale-102 hover:shadow-soft-xs active:opacity-85">Enviar enlace de recuperación</button>
</form> 