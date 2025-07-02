<!--
=========================================================
* Soft UI Dashboard Tailwind - v1.0.5
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard-tailwind
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">
@extends('layouts.app')

@section('title','profile')
@section('content')
@if (isset($recordatorios) && $recordatorios->isNotEmpty())
    @php
        // Ensure $hoy is a date string for consistent comparison
        $hoyString = $hoy->toDateString();

        $recordatoriosHoy = $recordatorios->filter(function ($rec) use ($hoyString) {
            return \Carbon\Carbon::parse($rec->fecha)->toDateString() === $hoyString;
        });

        $recordatoriosManana = $recordatorios->filter(function ($rec) use ($manana) {
            return \Carbon\Carbon::parse($rec->fecha)->toDateString() === $manana;
        });

        $recordatoriosPasado = $recordatorios->filter(function ($rec) use ($pasado) {
            return \Carbon\Carbon::parse($rec->fecha)->toDateString() === $pasado;
        });
    @endphp

    {{-- Reminders for Today --}}
    @if ($recordatoriosHoy->isNotEmpty())
        <h2 class="mt-6 text-xl font-bold dark:text-white">
            Recordatorios para Hoy
        </h2>
        @foreach ($recordatoriosHoy as $recordatorio)
            <div class="p-4 my-2 rounded-lg bg-red-200 text-red-600">
                <strong>{{ $recordatorio->titulo }}</strong><br>
                {{ $recordatorio->descripcion }}

                <form action="{{ route('recordatorios.visto', $recordatorio) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="mt-2 inline-block px-4 py-1.5 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                        👁️ Hecho
                    </button>
                </form>
            </div>
        @endforeach
    @endif

    {{-- Reminders for Tomorrow --}}
    @if ($recordatoriosManana->isNotEmpty())
        <h2 class="mt-6 text-xl font-bold dark:text-white">
            Recordatorios para Mañana
        </h2>
        @foreach ($recordatoriosManana as $recordatorio)
            <div class="p-4 my-2 rounded-lg bg-yellow-100">
                <strong>{{ $recordatorio->titulo }}</strong><br>
                {{ $recordatorio->descripcion }}

                <form action="{{ route('recordatorios.visto', $recordatorio) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="mt-2 inline-block px-4 py-1.5 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                        👁️ Hecho
                    </button>
                </form>
            </div>
        @endforeach
    @endif

    {{-- Reminders for the Day After Tomorrow --}}
    @if ($recordatoriosPasado->isNotEmpty())
        <h2 class="mt-6 text-xl font-bold dark:text-white">
            Recordatorios para Pasado Mañana
        </h2>
        @foreach ($recordatoriosPasado as $recordatorio)
            <div class="p-4 my-2 rounded-lg bg-green-100">
                <strong>{{ $recordatorio->titulo }}</strong><br>
                {{ $recordatorio->descripcion }}

                <form action="{{ route('recordatorios.visto', $recordatorio) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="mt-2 inline-block px-4 py-1.5 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                        👁️ Hecho
                    </button>
                </form>
            </div>
        @endforeach
    @endif
@elseif(auth()->user()->silenciar_notificaciones_web)
    <div class="p-4 my-2 rounded-lg bg-gray-200 text-gray-600 text-center">
        Notificaciones web silenciadas. No se mostrarán recordatorios aquí mientras esta opción esté activa.
    </div>
@endif
<body class="m-0 font-sans antialiased font-normal text-base leading-default bg-gray-50 text-slate-500">

<div class="bg-white text-black dark:bg-slate-800 dark:text-white p-4 rounded ease-soft-in-out relative h-full max-h-screen bg-gray-50 transition-all duration-200">
    <div class="w-full px-6 mx-auto dark:text-white">
        <div class="relative flex items-center p-0 mt-6 overflow-hidden bg-center bg-cover min-h-75 rounded-2xl"
             style="background-image: url('/assets/img/curved-images/curved0.jpg'); background-position-y: 50%">
            <span
                class="absolute inset-y-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-purple-700 to-pink-500 opacity-60"></span>
        </div>
        
        <div
            class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 -mt-16 overflow-hidden break-words border-0 shadow-blur rounded-2xl bg-white/80 bg-clip-border backdrop-blur-2xl backdrop-saturate-200">
            <div class="flex flex-wrap -mx-3">
                <div class="flex-none w-auto max-w-full px-3">
                    <div
                        class="text-base ease-soft-in-out h-18.5 w-18.5 relative inline-flex items-center justify-center rounded-xl text-white transition-all duration-200">
                        <img
                            src="{{ $usuario->foto ? asset('storage/' . $usuario->foto) : asset('storage/default/defaultUser.jpg') }}"
                            alt="profile_image"
                            class="w-full shadow-soft-sm rounded-xl"/>
                    </div>
                </div>
                <div class="flex-none w-auto max-w-full px-3 my-auto">
                    <div class="h-full">
                        <h5 class="mb-1">{{ $usuario->name }}</h5>
                    </div>
                </div>

                <div class="w-full max-w-full px-3 mx-auto mt-4 sm:my-auto sm:mr-0 md:w-1/2 md:flex-none lg:w-4/12">
                    <div class="relative right-0">
                        <ul class="relative flex flex-wrap p-1 list-none bg-transparent rounded-xl" nav-pills
                            role="tablist">
                            <li class="z-30 flex-auto text-center">
                                <a class="z-30 block w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-soft-in-out bg-inherit text-slate-700"
                                   nav-link active href="{{ route('recordatorios.calendario', ['usuario' => $usuario]) }}" role="tab" aria-selected="true">
                                    <svg class="text-slate-700" width="16px" height="16px" viewBox="0 0 42 42"
                                         version="1.1" xmlns="http://www.w3.org/2000/svg"
                                         xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF"
                                               fill-rule="nonzero">
                                                <g transform="translate(1716.000000, 291.000000)">
                                                    <g transform="translate(603.000000, 0.000000)">
                                                        <path class="fill-slate-800"
                                                              d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z"></path>
                                                        <path class="fill-slate-800"
                                                              d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z"
                                                              opacity="0.7"></path>
                                                        <path class="fill-slate-800"
                                                              d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z"
                                                              opacity="0.7"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <span class="ml-1">Calendario</span>
                                </a>
                            </li>
                            <li class="z-30 flex-auto text-center">
                                <a class="z-30 block w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-soft-in-out bg-inherit text-slate-700"
                                   nav-link href="{{ route('soporte.contacto') }}" role="tab" aria-selected="false">
                                    <svg class="text-slate-700" width="16px" height="16px" viewBox="0 0 40 44"
                                         version="1.1" xmlns="http://www.w3.org/2000/svg"
                                         xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>document</title>
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF"
                                               fill-rule="nonzero">
                                                <g transform="translate(1716.000000, 291.000000)">
                                                    <g transform="translate(154.000000, 300.000000)">
                                                        <path class="fill-slate-800"
                                                              d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z"
                                                              opacity="0.603585379"></path>
                                                        <path class="fill-slate-800"
                                                              d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <span class="ml-1">Soporte</span>
                                </a>
                            </li>
                            <li class="z-30 flex-auto text-center">
                                <a href="{{ route('usuarios.edit', $usuario) }}"
                                   class="z-30 block w-full px-0 py-1 mb-0 transition-colors border-0 rounded-lg ease-soft-in-out bg-inherit text-slate-700"
                                   nav-link href="javascript:;" role="tab" aria-selected="false">
                                    <svg class="text-slate-700" width="16px" height="16px" viewBox="0 0 40 40"
                                         version="1.1" xmlns="http://www.w3.org/2000/svg"
                                         xmlns:xlink="http://www.w3.org/1999/xlink">

                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF"
                                               fill-rule="nonzero">
                                                <g transform="translate(1716.000000, 291.000000)">
                                                    <g transform="translate(304.000000, 151.000000)">
                                                        <polygon class="fill-slate-800" opacity="0.596981957"
                                                                 points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667"></polygon>
                                                        <path class="fill-slate-800"
                                                              d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z"
                                                              opacity="0.596981957"></path>
                                                        <path class="fill-slate-800"
                                                              d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <span>Editar perfil</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white text-black dark:bg-slate-800 dark:text-white p-4 rounded  w-full p-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 xl:w-4/12">
                <div
                    class="bg-white text-black dark:bg-slate-400 dark:text-white relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="bg-white text-black dark:bg-slate-400 dark:text-white p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <h6 class="mb-0">Ajustes</h6>
                    </div>
                    <div class="flex-auto p-4">
                        <h6 class="bg-white text-black dark:bg-slate-400 dark:text-white font-bold leading-tight uppercase text-xs text-slate-500">Account</h6>
                        <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                            <li class="bg-white text-black dark:bg-slate-400 dark:text-white relative block px-0 py-2 border-0 rounded-t-lg">
                                <div class="min-h-6 mb-0.5 block pl-0">
                                    <form method="POST" action="{{ route('modo-oscuro.toggle') }}">
                                        @csrf
                                        <input
                                            id="modoOscuroSwitch"
                                            name="modo_oscuro"
                                            onchange="this.form.submit()"
                                            type="checkbox"
                                            {{ session('modo_oscuro') ? 'checked' : '' }}
                                        class="rounded-full relative w-10 h-5 bg-gray-300 checked:bg-slate-700 appearance-none cursor-pointer transition-all duration-300
                                        after:content-[''] after:absolute after:w-4 after:h-4 after:bg-white after:rounded-full after:top-0.5 after:left-0.5
                                        checked:after:translate-x-5 after:transition-transform"
                                        />

                                        <label for="modoOscuroSwitch" class="ml-4 text-sm text-slate-700 dark:text-white cursor-pointer">
                                            🌙 Modo oscuro
                                        </label>
                                    </form>
                                </div>
                            </li>
                            <li class="bg-white text-black dark:bg-slate-400 dark:text-white relative block px-0 py-2 bg-white border-0 text-inherit">
                            <div class="min-h-6 mb-0.5 block pl-0">
                    <form method="POST" action="{{ route('settings.toggle-email-notification') }}">
                        @csrf
                        <input
                            id="notificarEmailSwitch"
                            name="notificar_email"
                            type="checkbox"
                            onchange="this.form.submit()"
                            {{ Auth::user()->notificar_email ? 'checked' : '' }} {{-- Get state from authenticated user --}}
                            class="rounded-full relative w-10 h-5 bg-gray-300 checked:bg-slate-700 appearance-none cursor-pointer transition-all duration-300
                            after:content-[''] after:absolute after:w-4 after:h-4 after:bg-white after:rounded-full after:top-0.5 after:left-0.5
                            checked:after:translate-x-5 after:transition-transform"
                        />
                        <label for="notificarEmailSwitch" class="ml-4 text-sm text-slate-700 dark:text-white cursor-pointer">
                            Notificarme por email
                        </label>
                    </form>
                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="w-full max-w-full px-3 lg-max:mt-6 xl:w-4/12">
                <div
                    class="bg-white text-black dark:bg-slate-400 dark:text-white relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="bg-white text-black dark:bg-slate-400 dark:text-white p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <div class="flex flex-wrap -mx-3">
                            <div class="bg-white text-black dark:bg-slate-400 dark:text-white flex items-center w-full max-w-full px-3 shrink-0 md:w-8/12 md:flex-none">
                                <h6 class="bg-white text-black dark:bg-slate-400 dark:text-white mb-0">Información del perfil</h6>
                            </div>
                            <div class="bg-white text-black dark:bg-slate-400 dark:text-white w-full max-w-full px-3 text-right shrink-0 md:w-4/12 md:flex-none">
                                <a href="{{ route('usuarios.edit', $usuario) }}" data-target="tooltip_trigger" data-placement="top">
                                    <i class="leading-normal fas fa-user-edit text-sm text-slate-400"></i>
                                </a>
                                <div data-target="tooltip"
                                     class="hidden px-2 py-1 text-center text-white bg-black rounded-lg text-sm"
                                     role="tooltip">
                                    Edit Profile
                                    <div
                                        class="invisible absolute h-2 w-2 bg-inherit before:visible before:absolute before:h-2 before:w-2 before:rotate-45 before:bg-inherit before:content-['']"
                                        data-popper-arrow></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-auto p-4">
                        <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                            <li class="bg-white text-black dark:bg-slate-400 dark:text-white relative block px-4 py-2 pt-0 pl-0 leading-normal bg-white border-0 rounded-t-lg text-sm text-inherit">
                                <strong class=" text-slate-700">Nombre:</strong> &nbsp;{{ $usuario->name }}
                            </li>
                            <li class="bg-white text-black dark:bg-slate-400 dark:text-white relative block px-4 py-2 pl-0 leading-normal bg-white border-0 border-t-0 text-sm text-inherit">
                                <strong class="text-slate-700">Email:</strong> &nbsp; {{ $usuario->email }}
                            </li>
                            <li class="bg-white text-black dark:bg-slate-400 dark:text-white relative block px-4 py-2 pl-0 leading-normal bg-white border-0 border-t-0 text-sm text-inherit">
                                <strong class=" text-slate-700">Fecha de creación:</strong> &nbsp; {{
                                $usuario->created_at->format('d/m/Y') }}
                            </li>
                            <li class="bg-white text-black dark:bg-slate-400 dark:text-white relative block px-4 py-2 pl-0 leading-normal bg-white border-0 border-t-0 text-sm text-inherit">
                                <strong class="text-slate-700">Última actualización:</strong> &nbsp; {{
                                $usuario->updated_at->format('d/m/Y') }}
                            </li>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="bg-white text-black dark:bg-slate-800 dark:text-white p-4 rounded w-full max-w-full px-3 lg-max:mt-6 xl:w-4/12">
                <div
                    class="bg-white text-black dark:bg-slate-400 dark:text-white relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="bg-white text-black dark:bg-slate-400 dark:text-white p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <div class="bg-white text-black dark:bg-slate-400 dark:text-white flex flex-wrap -mx-3">
                            <div class="bg-white text-black dark:bg-slate-400 dark:text-white flex items-center w-full max-w-full px-3 shrink-0 md:w-8/12 md:flex-none">
                                <h6 class="mb-0 ">
                                    <a href="{{ route('recordatorios.index') }}"
                                       class="text-slate-800 hover:text-blue-600 transition-colors">
                                        Recordatorios
                                    </a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <table class="bg-white text-black dark:bg-slate-400 dark:text-white p-4 rounded  items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                        <tr>
                            <th class="bg-white text-black dark:bg-slate-400 dark:text-white px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                                Recordatorio
                            </th>
                            <th class="bg-white text-black dark:bg-slate-400 dark:text-white px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                                Mascota
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($recordatorios as $recordatorio)
                        <tr>
                            <td class=" bg-white text-black dark:bg-slate-400 dark:text-white p-4 rounded  p-4 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                <div class="flex px-2 py-1">
                                    <div class="flex flex-col justify-center">
                                        <h6 class="mb-0 text-sm leading-normal max-w-[100px]">
                                            <a href="{{ route('recordatorios.show', $recordatorio) }}"
                                               class="block truncate text-sm font-semibold text-slate-700 hover:text-blue-500">
                                                {{ $recordatorio->titulo }}
                                            </a>
                                        </h6>
                                        <span class="bg-white text-black dark:bg-slate-400 dark:text-white mb-0 text-xs leading-tight text-slate-400">
                                              {{ \Carbon\Carbon::parse($recordatorio->fecha)->format('d/m/Y') }}
                                           </span>
                                    </div>
                                </div>
                            </td>


                            <td class="bg-white text-black dark:bg-slate-400 dark:text-white p-4 rounded  p-4 max-w-[100px] align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                <div class="flex block truncate  flex-col justify-center">
                                <span>{{ $recordatorio->mascota->nombre }}</span>

                                <form action="{{ route('recordatorios.visto', $recordatorio) }}" method="POST"
                                      class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="mr-2 inline-block px-3 py-1 font-bold text-center bg-gradient-to-tl from-green-600 to-lime-400 uppercase align-middle transition-all rounded-md cursor-pointer text-xs ease-soft-in tracking-tight-soft shadow-sm bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">👁️ Visto</button>
                                </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <p class="text-gray-500">No hay recordatorios próximos.</p>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div
        class="bg-white text-black dark:bg-slate-800 dark:text-white p-4 rounded  relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent shadow-soft-xl rounded-2xl bg-clip-border">
        <div class="flex-auto px-0 pt-0 pb-2">
            <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                    <thead class="bg-white text-black dark:bg-slate-400 dark:text-white align-bottom bg-white text-black dark:bg-slate-500 dark:text-white p-4 rounded ">
                    <tr>
                        <th class="bg-white text-black dark:bg-slate-400 dark:text-white px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                            Foto
                        </th>
                        <th class="bg-white text-black dark:bg-slate-400 dark:text-white px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                            Nombre
                        </th>
                        <th class="bg-white text-black dark:bg-slate-400 dark:text-white px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                            Especie
                        </th>
                        <th class="bg-white text-black dark:bg-slate-400 dark:text-white px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                            Raza
                        </th>
                        <th class="bg-white text-black dark:bg-slate-400 dark:text-white px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                            Sexo
                        </th>
                        <th class="bg-white text-black dark:bg-slate-400 dark:text-white px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                            Usuario
                        </th>
                        <th class="bg-white text-black dark:bg-slate-400 dark:text-white px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                            Acciones
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($mascotas as $mascota)
                    <tr>
                        <td class="bg-white text-black dark:bg-slate-400 dark:text-white p-4 rounded  p-4 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                        <img src="{{ $mascota->imagen_url }}"
     class="h-10 w-10 rounded-xl object-cover">
                        </td>
                        <td class="bg-white text-black dark:bg-slate-400 dark:text-white p-4 rounded  p-4 max-w-[100px] align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                            <a href="{{ route('mascotas.show', $mascota) }}"
                               class="block truncate font-semibold text-sm text-slate-600 hover:text-blue-500">
                                {{ $mascota->nombre }}
                            </a>
                        </td>
                        <td class="bg-white text-black dark:bg-slate-400 dark:text-white p-4 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                <span
                                    class="text-xs font-medium text-slate-600">{{ ucfirst($mascota->especie->value) }}</span>
                        </td>
                        <td class="bg-white text-black dark:bg-slate-400 dark:text-white p-4 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                            <span class="text-xs text-slate-600">{{ $mascota->raza }}</span>
                        </td>
                        <td class="bg-white text-black dark:bg-slate-400 dark:text-white p-4 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                            <span class="text-xs text-slate-600">{{ $mascota->sexo?->value }}</span>
                        </td>
                        <td class="bg-white text-black dark:bg-slate-400 dark:text-white p-4 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                            <span class="text-xs text-slate-600">{{ $mascota->usuario->name ?? 'N/A' }}</span>
                        </td>
                        <td class="bg-white text-black dark:bg-slate-400 dark:text-white p-4 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                            <a href="{{ route('mascotas.edit', $mascota) }}"
                               class="mr-2 inline-block px-3 py-1 font-bold text-center bg-gradient-to-tl from-blue-600 to-cyan-400 uppercase align-middle transition-all rounded-md cursor-pointer text-xs ease-soft-in tracking-tight-soft shadow-sm bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">✏️ Editar</a>
                            <form action="{{ route('mascotas.destroy', $mascota) }}" method="POST"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('¿Seguro que quieres eliminar esta mascota?')"
                                        class="mr-2 inline-block px-3 py-1 font-bold text-center bg-gradient-to-tl from-red-600 to-rose-400 uppercase align-middle transition-all rounded-md cursor-pointer text-xs ease-soft-in tracking-tight-soft shadow-sm bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">🗑️
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $mascotas->links() }}
                </div>
            </div>
        </div>
        <footer class="pt-4">
            <div class="w-full px-6 mx-auto">
                <div class="flex flex-wrap items-center -mx-3 lg:justify-between">
                    <div class="w-full max-w-full px-3 mt-0 mb-6 shrink-0 lg:mb-0 lg:w-1/2 lg:flex-none">
                        <div class="leading-normal text-center text-sm text-slate-500 lg:text-left">
                            ©
                            <script>
                                document.write(new Date().getFullYear() + ",");
                            </script>
                            Hecho con <i class="fa fa-heart"></i> por
                            <a href="https://www.creative-tim.com" class="font-semibold text-slate-700"
                               target="_blank">
                                Luis</a>
                            para mejorar tu cuidado animal.
                        </div>
                    </div>
                    <div class="w-full max-w-full px-3 mt-0 shrink-0 lg:w-1/2 lg:flex-none">
                        <ul class="flex flex-wrap justify-center pl-0 mb-0 list-none lg:justify-end">
                            <li class="nav-item">
                                <a href="{{ route('about') }}"
                                   class="block px-4 pt-0 pb-1 font-normal transition-colors ease-soft-in-out text-sm text-slate-500">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('cookies') }}"
                                   class="block px-4 pt-0 pb-1 font-normal transition-colors ease-soft-in-out text-sm text-slate-500">cookies</a>
                            </li>
                            <li class="nav-item">
                                <a href="https://www.creative-tim.com/license"
                                   class="block px-4 pt-0 pb-1 pr-0 font-normal transition-colors ease-soft-in-out text-sm text-slate-500"
                                   target="_blank">License</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
<div fixed-plugin>
    <a fixed-plugin-button
       class="bottom-7.5 right-7.5 text-xl z-990 shadow-soft-lg rounded-circle fixed cursor-pointer bg-white px-4 py-2 text-slate-700">
        <i class="py-2 pointer-events-none fa fa-cog"> </i>
    </a>
    <!-- -right-90 in loc de 0-->
    <div fixed-plugin-card
         class="z-sticky shadow-soft-3xl w-90 ease-soft -right-90 fixed top-0 left-auto flex h-full min-w-0 flex-col break-words rounded-none border-0 bg-white bg-clip-border px-2.5 duration-200">
        <div class="px-6 pt-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
            <div class="float-left">
                <h5 class="mt-4 mb-0">Soft UI Configurator</h5>
                <p>See our dashboard options.</p>
            </div>
            <div class="float-right mt-6">
                <button fixed-plugin-close-button
                        class="inline-block p-0 mb-4 font-bold text-center uppercase align-middle transition-all bg-transparent border-0 rounded-lg shadow-none cursor-pointer hover:scale-102 leading-pro text-xs ease-soft-in tracking-tight-soft bg-150 bg-x-25 active:opacity-85 text-slate-700">
                    <i class="fa fa-close"></i>
                </button>
            </div>
            <!-- End Toggle Button -->
        </div>
        <hr class="h-px mx-0 my-1 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent"/>
        <div class="flex-auto p-6 pt-0 sm:pt-4">
            <!-- Sidebar Backgrounds -->
            <div>
                <h6 class="mb-0">Sidebar Colors</h6>
            </div>
            <a href="javascript:void(0)">
                <div class="my-2 text-left" sidenav-colors>
                    <span
                        class="text-xs rounded-circle h-5.75 mr-1.25 w-5.75 ease-soft-in-out bg-gradient-to-tl from-purple-700 to-pink-500 relative inline-block cursor-pointer whitespace-nowrap border border-solid border-slate-700 text-center align-baseline font-bold uppercase leading-none text-white transition-all duration-200 hover:border-slate-700"
                        active-color data-color-from="purple-700" data-color-to="pink-500"
                        onclick="sidebarColor(this)"></span>
                    <span
                        class="text-xs rounded-circle h-5.75 mr-1.25 w-5.75 ease-soft-in-out bg-gradient-to-tl from-gray-900 to-slate-800 relative inline-block cursor-pointer whitespace-nowrap border border-solid border-white text-center align-baseline font-bold uppercase leading-none text-white transition-all duration-200 hover:border-slate-700"
                        data-color-from="gray-900" data-color-to="slate-800" onclick="sidebarColor(this)"></span>
                    <span
                        class="text-xs rounded-circle h-5.75 mr-1.25 w-5.75 ease-soft-in-out bg-gradient-to-tl from-blue-600 to-cyan-400 relative inline-block cursor-pointer whitespace-nowrap border border-solid border-white text-center align-baseline font-bold uppercase leading-none text-white transition-all duration-200 hover:border-slate-700"
                        data-color-from="blue-600" data-color-to="cyan-400" onclick="sidebarColor(this)"></span>
                    <span
                        class="text-xs rounded-circle h-5.75 mr-1.25 w-5.75 ease-soft-in-out bg-gradient-to-tl from-green-600 to-lime-400 relative inline-block cursor-pointer whitespace-nowrap border border-solid border-white text-center align-baseline font-bold uppercase leading-none text-white transition-all duration-200 hover:border-slate-700"
                        data-color-from="green-600" data-color-to="lime-400" onclick="sidebarColor(this)"></span>
                    <span
                        class="text-xs rounded-circle h-5.75 mr-1.25 w-5.75 ease-soft-in-out bg-gradient-to-tl from-red-500 to-yellow-400 relative inline-block cursor-pointer whitespace-nowrap border border-solid border-white text-center align-baseline font-bold uppercase leading-none text-white transition-all duration-200 hover:border-slate-700"
                        data-color-from="red-500" data-color-to="yellow-400" onclick="sidebarColor(this)"></span>
                    <span
                        class="text-xs rounded-circle h-5.75 mr-1.25 w-5.75 ease-soft-in-out bg-gradient-to-tl from-red-600 to-rose-400 relative inline-block cursor-pointer whitespace-nowrap border border-solid border-white text-center align-baseline font-bold uppercase leading-none text-white transition-all duration-200 hover:border-slate-700"
                        data-color-from="red-600" data-color-to="rose-400" onclick="sidebarColor(this)"></span>
                </div>
            </a>
            <!-- Sidenav Type -->
            <div class="mt-4">
                <h6 class="mb-0">Sidenav Type</h6>
                <p class="leading-normal text-sm">Choose between 2 different sidenav types.</p>
            </div>
            <div class="flex">
                <button transparent-style-btn
                        class="inline-block w-full px-4 py-3 mb-2 font-bold text-center text-white uppercase align-middle transition-all border border-transparent border-solid rounded-lg cursor-pointer xl-max:cursor-not-allowed xl-max:opacity-65 xl-max:pointer-events-none xl-max:bg-gradient-to-tl xl-max:from-purple-700 xl-max:to-pink-500 xl-max:text-white xl-max:border-0 hover:scale-102 hover:shadow-soft-xs active:opacity-85 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 bg-gradient-to-tl from-purple-700 to-pink-500 bg-fuchsia-500 hover:border-fuchsia-500"
                        data-class="bg-transparent" active-style>Transparent
                </button>
                <button white-style-btn
                        class="inline-block w-full px-4 py-3 mb-2 ml-2 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg cursor-pointer xl-max:cursor-not-allowed xl-max:opacity-65 xl-max:pointer-events-none xl-max:bg-gradient-to-tl xl-max:from-purple-700 xl-max:to-pink-500 xl-max:text-white xl-max:border-0 hover:scale-102 hover:shadow-soft-xs active:opacity-85 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 border-fuchsia-500 bg-none text-fuchsia-500 hover:border-fuchsia-500"
                        data-class="bg-white">White
                </button>
            </div>
            <p class="block mt-2 leading-normal text-sm xl:hidden">You can change the sidenav type just on desktop
                view.</p>
            <!-- Navbar Fixed -->
            <div class="mt-4">
                <h6 class="mb-0">Navbar Fixed</h6>
            </div>
            <div class="min-h-6 mb-0.5 block pl-0">
                <input
                    class="rounded-10 duration-250 ease-soft-in-out after:rounded-circle after:shadow-soft-2xl after:duration-250 checked:after:translate-x-5.25 h-5 relative float-left mt-1 ml-auto w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-slate-800/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-slate-800/95 checked:bg-slate-800/95 checked:bg-none checked:bg-right"
                    type="checkbox" navbarFixed/>
            </div>
            <hr class="h-px bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent sm:my-6"/>
            <a class="inline-block w-full px-6 py-3 mb-4 font-bold text-center text-white uppercase align-middle transition-all bg-transparent border-0 rounded-lg cursor-pointer leading-pro text-xs ease-soft-in hover:shadow-soft-xs hover:scale-102 active:opacity-85 tracking-tight-soft shadow-soft-md bg-150 bg-x-25 bg-gradient-to-tl from-gray-900 to-slate-800"
               href="https://www.creative-tim.com/product/soft-ui-dashboard-tailwind" target="_blank">Free
                Download</a>
            <a class="inline-block w-full px-6 py-3 mb-4 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer active:shadow-soft-xs hover:scale-102 active:opacity-85 leading-pro text-xs ease-soft-in tracking-tight-soft bg-150 bg-x-25 border-slate-700 text-slate-700 hover:bg-transparent hover:text-slate-700 hover:shadow-none active:bg-slate-700 active:text-white active:hover:bg-transparent active:hover:text-slate-700 active:hover:shadow-none"
               href="https://www.creative-tim.com/learning-lab/tailwind/html/quick-start/soft-ui-dashboard/"
               target="_blank">View documentation</a>
            <div class="w-full text-center">
                <a class="github-button" href="https://github.com/creativetimofficial/soft-ui-dashboard-tailwind"
                   data-icon="octicon-star" data-size="large" data-show-count="true"
                   aria-label="Star creativetimofficial/soft-ui-dashboard on GitHub">Star</a>
                <h6 class="mt-4">Thank you for sharing!</h6>
                <a href="https://twitter.com/intent/tweet?text=Check%20Soft%20UI%20Dashboard%20Tailwind%20made%20by%20%40CreativeTim&hashtags=webdesign,dashboard,tailwindcss&amp;url=https%3A%2F%2Fwww.creative-tim.com%2Fproduct%2Fsoft-ui-dashboard-tailwind"
                   class="inline-block px-6 py-3 mb-0 mr-2 font-bold text-center text-white uppercase align-middle transition-all border-0 rounded-lg cursor-pointer hover:shadow-soft-xs hover:scale-102 active:opacity-85 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 me-2 border-slate-700 bg-slate-700"
                   target="_blank"> <i class="mr-1 fab fa-twitter" aria-hidden="true"></i> Tweet </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.creative-tim.com/product/soft-ui-dashboard-tailwind"
                   class="inline-block px-6 py-3 mb-0 mr-2 font-bold text-center text-white uppercase align-middle transition-all border-0 rounded-lg cursor-pointer hover:shadow-soft-xs hover:scale-102 active:opacity-85 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 me-2 border-slate-700 bg-slate-700"
                   target="_blank"> <i class="mr-1 fab fa-facebook-square" aria-hidden="true"></i> Share </a>
            </div>
        </div>
    </div>
</div>
</body>
@endsection


</html>
