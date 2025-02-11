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
            @elseif($icon === 'image')
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-gray-400">
                    <line x1="2" x2="22" y1="2" y2="22"/><path d="M10.41 10.41a2 2 0 1 1-2.83-2.83"/><line x1="13.5" x2="6" y1="13.5" y2="21"/><line x1="18" x2="21" y1="12" y2="15"/><path d="M3.59 3.59A1.99 1.99 0 0 0 3 5v14a2 2 0 0 0 2 2h14c.55 0 1.052-.22 1.41-.59"/><path d="M21 15V5a2 2 0 0 0-2-2H9"/></svg>
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
