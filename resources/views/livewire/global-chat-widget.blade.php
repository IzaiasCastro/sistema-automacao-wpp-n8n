<div 
    x-data="{ aberto: false }"
    style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;"
>
    {{-- 🔘 BOTÃO FLUTUANTE --}}
    <button 
        @click="aberto = !aberto"
        x-show="!aberto"
        x-transition
        style="
            background-color: #25D366;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            font-size: 28px;
            cursor: pointer;
        "
        title="Abrir chat"
    >
        💬
    </button>

    {{-- 💭 JANELA DO CHAT IA --}}
    <div 
        x-show="aberto"
        x-transition
        style="
            position: absolute;
            bottom: 70px;
            right: 0;
            width: 350px;
            max-height: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        "
    >
        {{-- Cabeçalho --}}
        <div 
            style="
                background-color: #25D366;
                color: white;
                padding: 10px;
                font-weight: 600;
                display: flex;
                justify-content: space-between;
                align-items: center;
            "
        >
            <div>
                🤖 Chat Inteligente
                <div style="font-size: 0.8rem;">Atendimento automatizado</div>
            </div>

            <button 
                @click="aberto = false"
                style="
                    background: transparent;
                    border: none;
                    color: white;
                    font-size: 18px;
                    cursor: pointer;
                    font-weight: bold;
                "
                title="Fechar"
            >
                ✕
            </button>
        </div>

        {{-- Aqui entra o teu chat dinâmico (ChatIa) --}}
        <div style="flex: 1; overflow-y: auto;">
            @livewire('chat-ia')
        </div>
    </div>
</div>
