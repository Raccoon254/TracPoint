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

<div>
    <div class="w-full max-w-md space-y-8 mb-8 relative z-10">
        <div class="text-center">
            <h2 class="text-4xl font-bold tracking-tight text-gray-900 mb-2">
                Create an account
            </h2>
            <p class="text-lg text-gray-600">
                Sign up to get started
            </p>
        </div>

        <form wire:submit="register" class="mt-8 space-y-6 bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-xl">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input
                    wire:model="name"
                    id="name"
                    class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-colors"
                    type="text"
                    name="name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Enter your name"
                />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input
                    wire:model="email"
                    id="email"
                    class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-colors"
                    type="email"
                    name="email"
                    required
                    autocomplete="username"
                    placeholder="Enter your email"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input
                    wire:model="password"
                    id="password"
                    class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-colors"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Enter your password"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input
                    wire:model="password_confirmation"
                    id="password_confirmation"
                    class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-colors"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm your password"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <a
                    class="text-sm font-medium text-emerald-600 hover:text-emerald-500 transition-colors"
                    href="{{ route('login') }}"
                    wire:navigate
                >
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ms-3 w-24 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-300">
                    <span wire:loading wire:target="register" class="flex items-center justify-center h-5 w-5">
                        <i data-lucide="loader-circle" class="w-5 h-5 animate-spin"></i>
                    </span>
                    <span wire:loading.remove wire:target="register">{{ __('Register') }}</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
