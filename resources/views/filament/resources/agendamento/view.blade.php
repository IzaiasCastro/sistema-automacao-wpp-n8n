<x-filament::page>
    <h1 class="text-xl font-bold">Visualizando Agendamento #{{ $record->id }}</h1>

    <p>Cliente: {{ $record->cliente->nome }}</p>
    <p>Serviço: {{ $record->servico->nome }}</p>
    <p>Profissional: {{ $record->profissional->nome }}</p>
    <p>Status: {{ $record->status }}</p>
</x-filament::page>
