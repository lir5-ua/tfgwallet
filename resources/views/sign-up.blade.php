@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<nav class="absolute top-0 z-30 flex flex-wrap items-center justify-between w-full px-4 py-2 mt-6 mb-4 shadow-none lg:flex-nowrap lg:justify-start">
    <div class="container flex items-center justify-between py-0 flex-wrap-inherit">
        <a class="py-2.375 text-sm mr-4 ml-4 whitespace-nowrap font-bold text-white lg:ml-0" href="/"> PetWallet </a>
        <button navbar-trigger class="px-3 py-1 ml-2 leading-none transition-all bg-transparent border border-transparent border-solid rounded-lg shadow-none cursor-pointer text-lg ease-soft-in-out lg:hidden" type="button" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
          <span class="inline-block mt-2 align-middle bg-center bg-no-repeat bg-cover w-6 h-6 bg-none">
            <span bar1 class="w-5.5 rounded-xs duration-350 relative my-0 mx-auto block h-px bg-white transition-all"></span>
            <span bar2 class="w-5.5 rounded-xs mt-1.75 duration-350 relative my-0 mx-auto block h-px bg-white transition-all"></span>
            <span bar3 class="w-5.5 rounded-xs mt-1.75 duration-350 relative my-0 mx-auto block h-px bg-white transition-all"></span>
          </span>
        </button>
        <div navbar-menu class="items-center flex-grow transition-all ease-soft duration-350 lg-max:bg-white lg-max:max-h-0 lg-max:overflow-hidden basis-full rounded-xl lg:flex lg:basis-auto">
            <ul class="flex flex-col pl-0 mx-auto mb-0 list-none lg:flex-row xl:ml-auto">
                <li>
                    <a class="flex items-center px-4 py-2 mr-2 font-normal text-white transition-all duration-250 lg-max:opacity-0 lg-max:text-slate-700 ease-soft-in-out text-sm lg:px-2 lg:hover:text-white/75" aria-current="page" href="/">Inicio</a>
                </li>
                <li>
                    <a class="block px-4 py-2 mr-2 font-normal text-white transition-all duration-250 lg-max:opacity-0 lg-max:text-slate-700 ease-soft-in-out text-sm lg:px-2 lg:hover:text-white/75" href="{{ url('/login') }}">Iniciar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<main class="mt-0 transition-all duration-200 ease-soft-in-out">
  <section class="min-h-screen mb-32">
    <div class="relative flex items-start pt-12 pb-56 m-4 overflow-hidden bg-center bg-cover min-h-50-screen rounded-xl" style="background-image: url('/assets/img/curved-images/curved14.jpg')">
      <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-gray-900 to-slate-800 opacity-60"></span>
      <div class="container z-10">
        <div class="flex flex-wrap justify-center -mx-3">
          <div class="w-full max-w-full px-3 mx-auto mt-0 text-center lg:flex-0 shrink-0 lg:w-5/12">
            <h1 class="mt-12 mb-2 text-white">¡Bienvenido!</h1>
            <p class="text-white">Crea tu cuenta para gestionar tus mascotas y recordatorios fácilmente.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="flex flex-wrap -mx-3 -mt-48 md:-mt-56 lg:-mt-48">
        <div class="w-full max-w-full px-3 mx-auto mt-0 md:flex-0 shrink-0 md:w-7/12 lg:w-5/12 xl:w-4/12">
          <div class="relative z-0 flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 mb-0 text-center bg-white border-b-0 rounded-t-2xl">
              <h5>Regístrate</h5>
            </div>
            <div class="flex-auto p-6">
              <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-4">
                  <input type="text" name="name" value="{{ old('name') }}" required
                         class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white py-2 px-3 text-gray-700 transition-all focus:border-blue-400 focus:outline-none"
                         placeholder="Nombre" />
                  @error('name') <small class="text-red-500">{{ $message }}</small> @enderror
                </div>
                <div class="mb-4">
                  <input type="email" name="email" value="{{ old('email') }}" required
                         class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white py-2 px-3 text-gray-700 transition-all focus:border-blue-400 focus:outline-none"
                         placeholder="Correo electrónico" />
                  @error('email') <small class="text-red-500">{{ $message }}</small> @enderror
                </div>
                <div class="mb-4">
                  <input type="password" name="password" required
                         class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white py-2 px-3 text-gray-700 transition-all focus:border-blue-400 focus:outline-none"
                         placeholder="Contraseña" />
                  @error('password') <small class="text-red-500">{{ $message }}</small> @enderror
                </div>
                <div class="mb-4">
                  <input type="password" name="password_confirmation" required
                         class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white py-2 px-3 text-gray-700 transition-all focus:border-blue-400 focus:outline-none"
                         placeholder="Confirmar contraseña" />
                </div>
                <div class="min-h-6 pl-6.92 mb-0.5 block">
                  <input id="terms" name="terms" type="checkbox" class="w-4.92 h-4.92 ease-soft -ml-6.92 rounded-1.4 checked:bg-gradient-to-tl checked:from-gray-900 checked:to-slate-800 duration-250 relative float-left mt-1 cursor-pointer appearance-none border border-solid border-slate-200 bg-white align-top transition-all checked:border-0 checked:border-transparent checked:bg-transparent" required />
                  <label class="mb-2 ml-1 font-normal cursor-pointer select-none text-sm text-slate-700" for="terms">Acepto los <a href="#" class="font-bold text-slate-700">Términos y Condiciones</a></label>
                  @if($errors->has('terms')) <br><small class="text-red-500">Debes aceptar los términos y condiciones.</small> @endif
                </div>
                <div class="text-center">
                  <button type="submit"
                          class="inline-block w-full px-6 py-3 mt-6 font-bold text-white uppercase transition-all bg-gradient-to-tl from-gray-900 to-slate-800 rounded-lg shadow-soft-md hover:scale-102 hover:shadow-soft-xs">
                    Registrarse
                  </button>
                </div>
                <p class="mt-4 mb-0 leading-normal text-sm">¿Ya tienes una cuenta? <a href="{{ url('/login') }}" class="font-bold text-slate-700">Inicia sesión</a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <footer class="py-12">
    <div class="container">
      <div class="flex flex-wrap -mx-3">
        <div class="flex-shrink-0 w-full max-w-full mx-auto mb-6 text-center lg:flex-0 lg:w-8/12">
          <a href="#" class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12">Sobre nosotros</a>
          <a href="#" class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12">Equipo</a>
          <a href="#" class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12">Blog</a>
        </div>
        <div class="flex-shrink-0 w-full max-w-full mx-auto mt-2 mb-6 text-center lg:flex-0 lg:w-8/12">
          <a href="#" class="mr-6 text-slate-400"><span class="text-lg fab fa-twitter"></span></a>
          <a href="#" class="mr-6 text-slate-400"><span class="text-lg fab fa-instagram"></span></a>
          <a href="#" class="mr-6 text-slate-400"><span class="text-lg fab fa-github"></span></a>
        </div>
      </div>
      <div class="flex flex-wrap -mx-3">
        <div class="w-8/12 max-w-full px-3 mx-auto mt-1 text-center flex-0">
          <p class="mb-0 text-slate-400">
            Proyecto creado en <script>document.write(new Date().getFullYear());</script> por Luis Ivorra.
          </p>
        </div>
      </div>
    </div>
  </footer>
</main>
@endsection 