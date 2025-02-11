<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>
<div>
    <div class="w-full max-w-md space-y-8 mb-8 relative z-10">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="text-center">
            <h2 class="text-4xl font-bold tracking-tight text-gray-900 mb-2">
                Welcome back
            </h2>
            <p class="text-lg text-gray-600">
                Sign in to your account
            </p>
        </div>

        <form wire:submit="login" class="mt-8 space-y-6 bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-xl">
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input
                    wire:model="form.email"
                    id="email"
                    class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-colors"
                    type="email"
                    name="email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter your email"
                />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input
                    wire:model="form.password"
                    id="password"
                    class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-colors"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember" class="inline-flex items-center">
                    <input
                        wire:model="form.remember"
                        id="remember"
                        type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 transition-colors"
                        name="remember"
                    />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-4">
                @if (Route::has('password.request'))
                    <a
                        class="text-sm font-medium text-emerald-600 hover:text-emerald-500 transition-colors"
                        href="{{ route('password.request') }}"
                        wire:navigate
                    >
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="ms-3 w-24 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-300">
                    <span wire:loading wire:target="login" class="flex items-center justify-center h-5 w-5">
                          <i data-lucide="loader-circle" class="w-5 h-5 animate-spin"></i>
                    </span>
                    <span wire:loading.remove wire:target="login">{{ __('Log in') }}</span>
                </x-primary-button>
            </div>

            <div class="text-center text-sm">
                <span class="text-gray-600">Don't have an account?</span>
                <a
                    href="{{ route('register') }}"
                    class="font-medium text-emerald-600 hover:text-emerald-500 transition-colors"
                    wire:navigate
                >
                    Sign up
                </a>
            </div>
        </form>
    </div>
</div>
