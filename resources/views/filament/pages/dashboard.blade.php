<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($this->getWidgets() as $widget)
            @livewire($widget)
        @endforeach
    </div>
</x-filament::page>
