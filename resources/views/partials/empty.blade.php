{{-- resources/views/partials/empty.blade.php --}}
<div class="flex flex-col items-center justify-center p-6 rounded-lg">
    @if(isset($icon))
        <div class="mb-4">
            {{-- Example inline SVG for an "exclamation-circle" icon.
                 You can extend this section with a switch statement or an icon component
                 if you have multiple icons to choose from. --}}
            @if($icon === 'exclamation-circle')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @else
                {{-- Fallback icon if no matching icon is found --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01" />
                </svg>
            @endif
        </div>
    @endif

    <h3 class="text-xl font-semibold text-gray-700">
        {{ $title ?? 'No Data Available' }}
    </h3>

    <p class="mt-2 text-sm text-gray-500">
        {{ $message ?? 'There is no data available to display.' }}
    </p>
</div>
