<div
    x-data="{ countdown: 60 }"
    x-init="
        let timer = setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                clearInterval(timer);

                // 🧹 Chama o Livewire pra deletar sessão se não conectou
                @this.call('destroySession');

                if (typeof close === 'function') {
                    close();
                } else {
                    document.querySelectorAll('button[x-on\\:click=\'close()\']').forEach(btn => btn.click());
                }
            }
        }, 600);

        // 🔁 Checar status no backend via Livewire
        let statusChecker = setInterval(async () => {
            @this.call('checkStatus');

            if (@this.active) {
                clearInterval(statusChecker);
                clearInterval(timer);
                await @this.call('activateSession');
            }
        }, 1000);
    "
    class="text-center space-y-4"
>
    @if ($active)
        <p class="text-green-600 font-bold text-lg">✅ Sessão conectada com sucesso!</p>
    @elseif ($qrcode)
        <img src="{!! $qrcode !!}" alt="QR Code da Sessão" style="width:350px;height:350px;">
        <p class="text-sm text-gray-600">Escaneie este QR Code com o WhatsApp.</p>

        <div class="mt-4">
            <p class="text-gray-800 font-bold text-lg">
                Fechando em <span x-text="countdown" class="text-red-600"></span> segundos...
            </p>
        </div>
    @else
        <p class="text-red-500 font-semibold">{{ $message ?? 'QR Code não disponível.' }}</p>
    @endif
</div>
