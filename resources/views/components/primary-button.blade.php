<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-gradient']) }}>
    {{ $slot }}
</button>
