<x-filament::widget>
    <x-filament::card>
        @if(App\Models\Cliente::where('user_id', auth()->user()->id)->first())
        <div class="text-center">
            <h2 class="text-lg font-bold">Seus Pontos</h2>
            <div class="text-3xl text-yellow-500 mt-2">{{ $points }}</div>
        </div>
        @endif
    </x-filament::card>
</x-filament::widget>