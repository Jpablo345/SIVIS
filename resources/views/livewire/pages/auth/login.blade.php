<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="w-full">
    <div class="flex flex-col items-center mb-8">
        <div class="w-24 h-24  p-2 shadow-lg ">
            <img src="{{ asset('img/logoufps.png') }}" alt="UFPS" class="w-full h-auto" />
        </div>
        <h2 class="mt-4 text-2xl font-black text-white uppercase tracking-tight">
            Inicio de sesión
        </h2>
        <div class="h-1 w-10 bg-red-600 mt-1"></div>
    </div>

    <div class="bg-zinc-900 p-8 rounded-2xl shadow-2xl border border-zinc-800">

        <x-auth-session-status class="mb-4 text-center text-red-400" :status="session('status')" />

        <form wire:submit="login" class="space-y-6">
            <div>
                <x-input-label for="email" :value="__('Correo Institucional')" class="font-bold text-zinc-300" />
                <x-text-input wire:model="form.email" id="email"
                    class="block mt-1 w-full border-zinc-700 bg-zinc-800 text-white focus:border-red-500 focus:ring-red-500"
                    type="email" name="email" required autofocus />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-red-500" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Contraseña')" class="font-bold text-zinc-300" />
                <x-text-input wire:model="form.password" id="password"
                    class="block mt-1 w-full border-zinc-700 bg-zinc-800 text-white focus:border-red-500 focus:ring-red-500"
                    type="password" name="password" required />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-red-500" />
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center">
                    <input wire:model="form.remember" type="checkbox"
                        class="rounded border-zinc-700 bg-zinc-800 text-red-600 focus:ring-red-500">
                    <span class="ms-2 text-xs text-zinc-400">Recordarme</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-red-500 hover:text-red-400 transition-colors"
                        href="{{ route('password.request') }}">
                        ¿Olvidaste tu clave?
                    </a>
                @endif
            </div>

            <div class="flex flex-col gap-3 pt-4">
                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-black py-3 rounded-lg shadow-lg shadow-red-900/50 transition-all uppercase text-sm tracking-widest">
                    {{ __('Entrar') }}
                </button>

                <a href="{{ route('register') }}" wire:navigate class="w-full text-center">
                    <span class="text-xs text-zinc-500 block mb-2 font-bold uppercase tracking-widest">¿No tienes
                        cuenta?</span>
                    <button type="button"
                        class="w-full bg-transparent border border-zinc-700 text-zinc-300 font-bold py-3 rounded-lg hover:bg-zinc-800 transition-all uppercase text-sm tracking-widest">
                        {{ __('Registrarse') }}
                    </button>
                </a>
            </div>
        </form>
    </div>

    <div class="mt-8 text-center">
        <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-[0.2em]">
            Sistema de Visualización de Investigaciones de Sistemas
        </p>
    </div>
</div>