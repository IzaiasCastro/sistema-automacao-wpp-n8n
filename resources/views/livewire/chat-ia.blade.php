<div class="flex flex-col-reverse h-96 rounded-lg shadow-md" style="background-color: #ECE5DD;">
    <!-- Área das mensagens -->
    <div id="chat-box" class="flex-1 overflow-y-auto p-4 flex flex-col-reverse" style="display:flex; gap:0.75rem;">
        @foreach (array_reverse($mensagens) as $msg)
            <div class="flex {{ $msg['autor'] === 'Você' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs md:max-w-md px-3 py-2 rounded-2xl text-sm shadow"
                     style="
                        {{ $msg['autor'] === 'Você'
                            ? "background-color: #25D366; color: #ffffff; border-bottom-right-radius: 0;"
                            : "background-color: #FFFFFF; color: #075E54; border-bottom-left-radius: 0;"
                        }}
                     ">
                    <p class="whitespace-pre-line">{{ $msg['texto'] }}</p>
                    <span class="block text-[10px] mt-1 opacity-70 text-right">
                        {{ $msg['autor'] }}
                    </span>
                </div>
            </div>
        @endforeach

        <div wire:loading wire:target="enviar" class="flex justify-start">
            <div class="max-w-xs px-3 py-2 rounded-2xl text-sm shadow" style="background-color:#FFFFFF; color:#075E54;">
                <span class="block text-sm">Digitando</span>
                <span class="dot-flashing" style="color:#25D366;"></span>
            </div>
        </div>
    </div>

    <!-- Perguntas rápidas -->
    <div class="flex flex-wrap gap-2 p-2 border-t" style="border-color: rgba(18,140,126,0.2); background-color: #D9FDD3;">
        @php
            $perguntasRapidas = [
                // 'Não vou trabalhar hoje',
                // 'Quantos clientes eu vou atender hoje?',
                // 'Quero colocar um limite para os agendamentos hoje',
            ];
        @endphp

        @foreach ($perguntasRapidas as $pergunta)
            <button
                type="button"
                wire:click="$set('entrada', '{{ $pergunta }}'); $wire.call('enviar');"
                class="px-3 py-1 text-sm rounded-full transition"
                style="background-color:#DCF8C6; color:#075E54;"
            >
                {{ $pergunta }}
            </button>
        @endforeach
    </div>

    <!-- Campo de entrada -->
    <form wire:submit.prevent="enviar" class="flex items-center border-t p-2" style="border-color: rgba(18,140,126,0.2); background-color: #F0F2F5;">
        <input
            id="entrada"
            wire:model.defer="entrada"
            type="text"
            placeholder="Digite sua mensagem..."
            class="flex-1 px-4 py-2 text-sm rounded-full border"
            style="border-color: rgba(18,140,126,0.3); background-color: #FFFFFF; color:#075E54;"
        >
        <button
            type="submit"
            class="ml-2 px-4 py-2 rounded-full transition"
            style="background-color:#25D366; color:#ffffff;"
        >
            <!-- ícone -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
            </svg>
        </button>
    </form>

    <style>
    .dot-flashing {
        position: relative;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: currentColor;
        animation: dot-flashing 1s infinite linear alternate;
    }
    .dot-flashing::before,
    .dot-flashing::after {
        content: '';
        display: inline-block;
        position: absolute;
        top: 0;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: currentColor;
    }
    .dot-flashing::before { left: -10px; animation-delay: 0s; }
    .dot-flashing::after { left: 10px; animation-delay: 1s; }
    @keyframes dot-flashing {
        0% { opacity: .2; } 50%,100% { opacity: 1; }
    }
    </style>
</div>
