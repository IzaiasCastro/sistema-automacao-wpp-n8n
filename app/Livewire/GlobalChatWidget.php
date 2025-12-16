<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * Componente Livewire flutuante para o chat global.
 * Fica posicionado no canto da tela via CSS.
 */
class GlobalChatWidget extends Component
{
    /**
     * @var bool Indica se a janela do chat está aberta.
     */
    public bool $isOpen = false;

    /**
     * Alterna o estado de abertura/fechamento do chat.
     */
    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    /**
     * Renderiza o componente.
     */
    public function render()
    {
        // Mock data para demonstração do histórico de mensagens
        $messages = [
            ['id' => 1, 'text' => 'Olá! Como posso te ajudar com o agendamento?', 'sender' => 'client'],
            ['id' => 2, 'text' => 'Estou verificando os horários da Jane.', 'sender' => 'owner'],
        ];

        return view('livewire.global-chat-widget', [
            'messages' => $messages,
        ]);
    }
}