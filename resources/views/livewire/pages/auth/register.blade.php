<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="w-full">
    <div class="flex flex-col items-center mb-8">
        <div class="w-24 h-24  p-2 shadow-lg ">
            <img src="{{ asset('img/logoufps.png') }}" alt="UFPS" class="w-full h-auto" />
        </div>
        <h2 class="mt-4 text-xl font-black text-white uppercase tracking-tight">
            Registro Institucional
        </h2>
        <div class="h-1 w-10 bg-red-600 mt-1"></div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-2xl border border-red-200">
        <form wire:submit="register" class="space-y-5">
            
            <div>
                <x-input-label for="name" :value="__('Nombre Completo')" class="font-bold text-zinc-700" />
                <x-text-input wire:model="name" id="name" class="block mt-1 w-full border-red-200 bg-white text-zinc-900 focus:border-red-500 focus:ring-red-500" type="text" name="name" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-600" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Correo Electrónico')" class="font-bold text-zinc-700" />
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full border-red-200 bg-white text-zinc-900 focus:border-red-500 focus:ring-red-500" type="email" name="email" required />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-600" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Contraseña')" class="font-bold text-zinc-700" />
                <x-text-input wire:model="password" id="password" class="block mt-1 w-full border-red-200 bg-white text-zinc-900 focus:border-red-500 focus:ring-red-500" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-600" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="font-bold text-zinc-700" />
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full border-red-200 bg-white text-zinc-900 focus:border-red-500 focus:ring-red-500" type="password" name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-600" />
            </div>

            <div class="flex flex-col gap-3 pt-4">
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-black py-3 rounded-lg shadow-lg shadow-red-900/50 transition-all uppercase text-sm tracking-widest">
                    {{ __('Crear Cuenta') }}
                </button>

                <div class="text-center pt-2">
                    <a class="text-xs font-bold text-red-700 hover:text-red-600 transition-colors uppercase tracking-widest" href="{{ route('login') }}" wire:navigate>
                        {{ __('¿Ya tienes cuenta? Inicia sesión') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="mt-8 text-center">
        <p class="text-[10px] text-white/70 font-bold uppercase tracking-[0.2em]">
            Universidad Francisco de Paula Santander - Ocaña • 2026
        </p>
    </div>
</div>