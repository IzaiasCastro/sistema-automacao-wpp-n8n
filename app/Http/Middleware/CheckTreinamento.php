<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTreinamento
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next)
    {
        if (! auth()->check()) {
            return $next($request);
        }

        $user = \App\Models\User::find(auth()->user()->id);

        if ($user->treinado) {
            return $next($request);
        }

        // evita loop infinito: se já estivermos na página de treinamento, deixa passar
        $isTrainingRoute = $request->routeIs('filament.admin.pages.treinamento-inicial')
            || $request->is('*treinamento-inicial*');

        if ($isTrainingRoute) {
            return $next($request);
        }

        // tenta pegar tenant da rota atual (por exemplo /admin/{tenant}/...)
        $tenant = $request->route('tenant') ?? null;

        // fallback para filament()->getTenant() se disponível
        try {
            if (! $tenant && function_exists('filament')) {
                $tenant = filament()->getTenant()->getKey();
            }
        } catch (\Throwable $e) {
            $tenant = $tenant; // keep null se não tiver tenant
        }

        if ($tenant) {
            return redirect()->route('filament.admin.pages.treinamento-inicial', [
                'tenant' => $tenant,
            ]);
        }

        // último recurso: redirect por URL (evita gerar rota nomeada sem tenant)
        return redirect("/admin/{$tenant}/treinamento-inicial");
    }
}
