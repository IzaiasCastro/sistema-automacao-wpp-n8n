<x-filament-panels::page>
    <div class="fi-page-content-wrapper mx-auto w-full max-w-7xl px-4 md:px-6 lg:px-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
            Guia Rápido de Configuração
        </h2>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Siga os passos abaixo para configurar sua aplicação. Recomendamos esta ordem.
        </p>

        {{-- Layout de Grid para os Cards --}}
        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

            @php
                $steps = [
                    [
                        'title' => '1. Cadastrar Profissionais',
                        'description' => 'Comece registrando seus funcionários/colaboradores que atenderão aos clientes.',
                        'icon' => 'heroicon-o-users',
                        'color' => 'bg-primary-500/10 text-primary-600',
                        'link' => \App\Filament\Resources\ProfissionalResource::getUrl('index'),
                    ],
                    [
                        'title' => '2. Cadastrar Serviços',
                        'description' => 'Defina os serviços oferecidos, duração e preço de cada um.',
                        'icon' => 'heroicon-o-wrench-screwdriver',
                        'color' => 'bg-success-500/10 text-success-600',
                        'link' => \App\Filament\Resources\ServicoResource::getUrl('index'),
                    ],
                    [
                        'title' => '3. Criar Agendas',
                        'description' => 'Configure a disponibilidade e os horários de trabalho de cada profissional.',
                        'icon' => 'heroicon-o-calendar',
                        'color' => 'bg-warning-500/10 text-warning-600',
                        'link' => \App\Filament\Pages\Agendas::getUrl(), // Ajuste o URL se for uma página
                    ],
                    [
                        'title' => '4. Gerenciar Clientes',
                        'description' => 'Acesse o cadastro de clientes ou adicione um novo para o primeiro agendamento.',
                        'icon' => 'heroicon-o-user-group',
                        'color' => 'bg-info-500/10 text-info-600',
                        'link' => \App\Filament\Resources\ClienteResource::getUrl('index'),
                    ],
                    [
                        'title' => '5. Novo Agendamento',
                        'description' => 'Agora sim! Agende um serviço para um cliente com um profissional disponível.',
                        'icon' => 'heroicon-o-clipboard-document-list',
                        'color' => 'bg-danger-500/10 text-danger-600',
                        'link' => \App\Filament\Resources\AgendamentoResource::getUrl('create'),
                    ],
                ];
            @endphp

            @foreach ($steps as $step)
                {{-- Card Individual --}}
                <div class="overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-gray-950/5 transition duration-300 hover:shadow-xl dark:bg-gray-900 dark:ring-white/10 dark:hover:shadow-2xl">
                    <div class="p-6">
                        {{-- Ícone do Passo --}}
                        <div class="h-10 w-10 flex items-center justify-center rounded-full {{ $step['color'] }} mb-4">
                            <x-filament::icon :icon="$step['icon']" class="h-6 w-6" />
                        </div>

                        {{-- Título --}}
                        <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                            {{ $step['title'] }}
                        </h3>

                        {{-- Descrição --}}
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            {{ $step['description'] }}
                        </p>

                        {{-- Botão de Ação --}}
                        <div class="mt-4">
                            <a href="{{ $step['link'] }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-500 dark:hover:text-primary-400">
                                Ir para a Configuração
                                <x-filament::icon icon="heroicon-m-arrow-right" class="ml-1 h-4 w-4" />
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</x-filament-panels::page>