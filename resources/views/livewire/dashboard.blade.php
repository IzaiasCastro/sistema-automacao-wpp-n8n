<div class="max-w-7xl mx-auto px-6 py-10">
    <h2 class="text-2xl font-semibold mb-6 text-gray-700">Painel de Controle</h2>

    <!-- Grid de Cards -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 flex flex-col justify-between">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-600">Agendamentos Hoje</h3>
                <span class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                    <i class="fa-solid fa-calendar-check"></i>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-800">12</p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 flex flex-col justify-between">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-600">Clientes Atendidos</h3>
                <span class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                    <i class="fa-solid fa-users"></i>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-800">38</p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 flex flex-col justify-between">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-600">Lucro (Mês)</h3>
                <span class="p-2 bg-yellow-100 text-yellow-600 rounded-lg">
                    <i class="fa-solid fa-coins"></i>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-800">R$ 2.540,00</p>
        </div>
    </div>

    <!-- Gráfico (exemplo usando ApexCharts) -->
    <div class="bg-white rounded-2xl shadow-sm mt-8 p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-700">Agendamentos da Semana</h3>
        <div wire:ignore id="grafico-agendamentos" class="h-64"></div>
    </div>

    <!-- Chat flutuante -->
    <div class="fixed bottom-6 right-6">
        @livewire('chat-ia')
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:load', () => {
    var options = {
        chart: { type: 'area', height: 250 },
        series: [{
            name: 'Agendamentos',
            data: [10, 15, 8, 20, 25, 18, 30]
        }],
        xaxis: {
            categories: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom']
        },
        colors: ['#10b981']
    };
    var chart = new ApexCharts(document.querySelector("#grafico-agendamentos"), options);
    chart.render();
});
</script>
@endpush
