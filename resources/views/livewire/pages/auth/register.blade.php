<?php

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    // Determines which step of the registration flow to show (1 = organization, 2 = user info)
    public int $step = 1;

    // Step 1 fields
    public ?int $organization_id = null;
    public string $verification_code = '';

    // Step 2 fields
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $organizations;

    public function mount(): void
    {
        $this->organizations = Organization::all();
    }

    /**
     * Validates and verifies the selected organization and its verification code.
     */
    public function verifyOrganization(): void
    {
        $this->validate([
            'organization_id' => ['required', 'exists:organizations,id'],
            'verification_code' => ['required', 'string'],
        ]);


        $organization = Organization::find($this->organization_id);

        if ($organization->verification_code !== $this->verification_code) {
            $this->addError('verification_code', 'The verification code is incorrect.');
            return;
        }

        // Proceed to user registration details
        $this->step = 2;
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $this->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'            => $this->name,
            'email'           => $this->email,
            'password'        => Hash::make($this->password),
            'role'            => 'admin',
            'organization_id' => $this->organization_id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="w-full max-w-md space-y-8 mb-8 relative z-10">
        <div class="text-center">
            <h2 class="text-4xl font-bold tracking-tight text-gray-900 mb-2">Create an account</h2>
            <p class="text-lg text-gray-600">Sign up to get started</p>
        </div>

        @if($step === 1)
            <form wire:submit.prevent="verifyOrganization"
                  class="mt-8 space-y-6 bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-xl">
                <!-- Organization Selection -->
                <div>
                    <x-input-label for="organization" :value="__('Organization')"/>
                    <select wire:model="organization_id" id="organization"
                            class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-colors">
                        <option value="">Select an organization</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('organization_id')" class="mt-2"/>
                </div>

                <!-- Verification Code -->
                <div>
                    <x-input-label for="verification_code" :value="__('Verification Code')"/>
                    <x-text-input
                        wire:model="verification_code"
                        id="verification_code"
                        class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-colors"
                        type="text"
                        placeholder="Enter organization verification code"
                    />
                    <x-input-error :messages="$errors->get('verification_code')" class="mt-2"/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="ms-3 w-24">
                        <span wire:loading wire:target="verifyOrganization" class="flex items-center justify-center h-5 w-5">
                            <i data-lucide="loader-circle" class="w-5 h-5 animate-spin"></i>
                        </span>
                        <span wire:loading.remove wire:target="verifyOrganization">{{ __('Next') }}</span>
                    </x-primary-button>
                </div>
            </form>
        @endif

        @if($step === 2)
            <form wire:submit.prevent="register"
                  class="mt-8 space-y-6 bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-xl">
                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')"/>
                    <x-text-input
                        wire:model="name"
                        id="name"
                        class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-colors"
                        type="text"
                        placeholder="Enter your name"
                        required
                        autofocus
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                </div>

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')"/>
                    <x-text-input
                        wire:model="email"
                        id="email"
                        class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-colors"
                        type="email"
                        placeholder="Enter your email"
                        required
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')"/>
                    <x-text-input
                        wire:model="password"
                        id="password"
                        class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-colors"
                        type="password"
                        placeholder="Enter your password"
                        required
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')"/>
                    <x-text-input
                        wire:model="password_confirmation"
                        id="password_confirmation"
                        class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-colors"
                        type="password"
                        placeholder="Confirm your password"
                        required
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <button type="button" wire:click="$set('step', 1)"
                            class="text-sm font-medium text-emerald-600 hover:text-emerald-500 transition-colors">
                        Back
                    </button>
                    <x-primary-button class="ms-3">
                    <span wire:loading wire:target="register" class="flex items-center justify-center h-5 w-5">
                        <i data-lucide="loader-circle" class="w-5 h-5 animate-spin"></i>
                    </span>
                        <span wire:loading.remove wire:target="register">{{ __('Register') }}</span>
                    </x-primary-button>
                </div>
            </form>
        @endif
    </div>
</div>
