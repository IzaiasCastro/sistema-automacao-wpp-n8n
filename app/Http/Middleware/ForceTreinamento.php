<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForceTreinamento
{
    public function handle(Request $request, Closure $next)
    {

          // 1) Permitir qualquer requisição AJAX/JSON/Livewire sem redirecionar
    // - Livewire adiciona header X-Livewire: true
    // - requisições AJAX geralmente têm X-Requested-With: XMLHttpRequest
    // - accepts JSON / expectsJson cobre fetch/fetch API
    if (
        $request->header('X-Livewire') === 'true' ||
        $request->header('X-Requested-With') === 'XMLHttpRequest' ||
        $request->expectsJson() ||
        $request->ajax() ||
        // rota do Livewire: /livewire/message/...
        Str::startsWith($request->path(), 'livewire/')
    ) {
        return $next($request);
    }

        // usuário deslogado → deixa seguir
        if (!auth()->check()) {
            return $next($request);
        }
        

        $user = \App\Models\User::find(auth()->user()->id);

        // se já treinado → segue normal
        if ($user->treinado) {
            return $next($request);
        }

        // permitir a própria página de treinamento
        if ($request->routeIs('filament.admin.pages.treinamento-inicial')) {
            return $next($request);
        }

        // permitir rotas de logout ou assets
        if ($request->routeIs('filament.*.auth.*')) {
            return $next($request);
        }
        $getTenants = \App\Models\User::find(auth()->user()->id)->getTenants(Filament::getCurrentPanel());
        if(count($getTenants) > 1){
            return $next($request);
        }

        if($user->treinado){
            return $next($request);
        }

        return  redirect($getTenants->first()->id . "/treinamento-inicial");
        
        

    }
}
