<?php

namespace App\Observers;

use App\Models\Organization;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SessaoWhatsapp;
use App\Models\User;
use App\Permissions\AgendamentoPermissions;
use App\Permissions\AgendaPermissions;
use App\Permissions\ClientePermissions;
use App\Permissions\PermissionPermissions;
use App\Permissions\PointTransactionPermissions;
use App\Permissions\ProfissionalPermissions;
use App\Permissions\RewardPermissions;
use App\Permissions\RolePermissions;
use App\Permissions\ServicoPermissions;
use App\Permissions\SessaoWhatsappPermissions;
use App\Permissions\UserPermissions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OrganizationObserver
{
    /**
     * Handle the Cliente "created" event.
     */
    public function created(Organization $organization): void
    {
        //criar workflow n8n e criar a sessao de whatsapp
        $sessaoWhats = SessaoWhatsapp::create([
            'organization_id' => $organization->id,
            'webhook' => '#'
        ]);
        
        $n8n = $this->generateWorkflowN8n($organization, $sessaoWhats->session_name);

        $sessaoWhats->update([
            'webhook' => $n8n['webhooks'][0]['full_url']
        ]);

        //vincular o super admin master com essa organization
        $user = User::find(1);
        DB::table('organization_user')->insert([
            'organization_id' => $organization->id,
            'user_id' => $user->id,
        ]);

          // Exemplo de roles padrão
        $roles = [
            'Propietario',
            'Profissional',
            'Cliente',
        ];

        foreach ($roles as $roleName) {
            $role = Role::create([
                'organization_id' => $organization->id,
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            // 2️⃣ Atribui permissões conforme o tipo de role
            switch ($roleName) {
                case 'Propietario':
                    // Admin tem todas
                    $agendamento = collect(AgendamentoPermissions::cases())->map(fn ($p) => $p->value);
                    $agenda = collect(AgendaPermissions::cases())->map(fn ($p) => $p->value);
                    $cliente = collect(ClientePermissions::cases())->map(fn ($p) => $p->value);
                    $pointTransaction = collect(PointTransactionPermissions::cases())->map(fn ($p) => $p->value);
                    $profissional = collect(ProfissionalPermissions::cases())->map(fn ($p) => $p->value);
                    $reward = collect(RewardPermissions::cases())->map(fn ($p) => $p->value);
                    $servico = collect(ServicoPermissions::cases())->map(fn ($p) => $p->value);
                    $sessao = collect(SessaoWhatsappPermissions::cases())->map(fn ($p) => $p->value);

                    $this->syncPermissions($role, $agendamento->toArray());
                    $this->syncPermissions($role, $agenda->toArray());
                    $this->syncPermissions($role, $cliente->toArray());
                    $this->syncPermissions($role, $pointTransaction->toArray());
                    $this->syncPermissions($role, $profissional->toArray());
                    $this->syncPermissions($role, $reward->toArray());
                    $this->syncPermissions($role, $servico->toArray());
                    $this->syncPermissions($role, $sessao->toArray());
                    break;

                case 'Profissional':
                    // Profissional tem todas
                    $agendamento = collect(AgendamentoPermissions::cases())->map(fn ($p) => $p->value);
                    $agenda = collect(AgendaPermissions::cases())->map(fn ($p) => $p->value);
                    $cliente = collect(ClientePermissions::cases())->map(fn ($p) => $p->value);
                    $pointTransaction = collect(PointTransactionPermissions::cases())->map(fn ($p) => $p->value);
                    $this->syncPermissions($role, $agendamento->toArray());
                    break;

                case 'Cliente':
                    // $this->syncPermissions($role, $agendamento->toArray());
                    break;
            }
        }

        $this->criarUserPropietario($organization);
    }

    protected function syncPermissions(Role $role, array $permissions): void
    {
        // Garante que as permissions existem antes de sincronizar
        $permissionModels = collect($permissions)->map(function ($name) {
            return Permission::where('name', $name)->where('guard_name', 'web')->first();
        });

        $permissionModels->each(function ($permission) use ($role) {
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permission->id,
                'role_id' => $role->id,
            ]);
        });
    }

    protected function generateWorkflowN8n(Organization $organization, $sessaoName)
    {
        $n8nHost = config('services.n8n.url_base');
        $apiKey = config('services.n8n.apiKey');
        $modeloId = config('services.n8n.modeloId');

        // 1️⃣ Buscar workflow modelo
        $response = Http::withHeaders([
            'X-N8N-API-KEY' => $apiKey,
            'accept' => 'application/json',
        ])->get("$n8nHost/api/v1/workflows/$modeloId");

        if ($response->failed()) {
            return [
                'error' => 'Falha ao buscar workflow modelo',
                'details' => $response->json(),
            ];
        }

        $workflow = $response->json();

        // 2️⃣ Atualizar nome e corrigir nodes
        $novoNome ='Zaptend - Cliente ' . $organization->name . ' - ' . now()->format('Y-m-d H:i:s');

        foreach ($workflow['nodes'] as &$node) {
            // Corrige parameters inválidos
            if (!isset($node['parameters']) || !is_array($node['parameters'])) {
                $node['parameters'] = new \stdClass();
            }

            if (is_array($node['parameters']) && array_is_list($node['parameters'])) {
                $node['parameters'] = new \stdClass();
            }

            // Corrige credentials inválidas
            if (isset($node['credentials']) && !is_array($node['credentials'])) {
                $node['credentials'] = new \stdClass();
            }

            // Atualiza o caminho do webhook
            if ($node['type'] === 'n8n-nodes-base.webhook') {
                $node['parameters']['path'] = $sessaoName;
            }
            //buscar cliente http
            if ($node['id'] === 'bf5e8ec4-c8e8-4ced-865c-c41b4c615a9f') {
                $node['parameters']['queryParameters']['parameters'][1]['value'] = rtrim($n8nHost, '/') . '/webhook/' . ($sessaoName);
            }
            //set criar cliente
            if ($node['id'] === '797d82a5-8468-45ad-aedf-392a739620aa') {
                $node['parameters']['assignments']['assignments'][2]['value'] = rtrim($n8nHost, '/') . '/webhook/' . ($sessaoName);
            }
            //http criar cliente
            if ($node['id'] === '39d2bf40-2902-4e73-9275-68b13fcb71a1') {
                $node['parameters']['queryParameters']['parameters'][0]['value'] = rtrim($n8nHost, '/') . '/webhook/' . ($sessaoName);
            }
            //Verificar Agenda2
            if ($node['id'] === 'b462dac2-deb6-4e57-bcdb-c8b9773f3537') {
                $node['parameters']['queryParameters']['parameters'][0]['value'] = rtrim($n8nHost, '/') . '/webhook/' . ($sessaoName);
            }
            //Verificar HTTP Request2
            if ($node['id'] === 'dc54a377-be60-4419-98ee-4fa5128f5e75') {
                $node['parameters']['queryParameters']['parameters'][0]['value'] = rtrim($n8nHost, '/') . '/webhook/' . ($sessaoName);
            }
        }

        // 3️⃣ Monta payload limpo
        $workflowData = [
            'name' => $novoNome,
            'nodes' => $workflow['nodes'] ?? [],
            'connections' => $workflow['connections'] ?? [],
            'settings' => $workflow['settings'] ?? new \stdClass(),
            // 🚫 NÃO enviar tags — é read-only
        ];

        // 4️⃣ Cria o novo workflow
        $createResponse = Http::withHeaders([
            'X-N8N-API-KEY' => $apiKey,
            'accept' => 'application/json',
        ])->post("$n8nHost/api/v1/workflows", $workflowData);

        if ($createResponse->failed()) {
            return response()->json([
                'error' => 'Falha ao criar novo workflow',
                'details' => $createResponse->json(),
            ], 500);
        }

        $novoWorkflow = $createResponse->json();
        $novoId = $novoWorkflow['id'] ?? null;

        // 5️⃣ Ativar o novo workflow
        if ($novoId) {
            Http::withHeaders([
                'X-N8N-API-KEY' => $apiKey,
                'accept' => 'application/json',
            ])->patch("$n8nHost/api/v1/workflows/$novoId", [
                'active' => true,
            ]);
        }

        // 6️⃣ Retornar resultado
        $webhooks = collect($novoWorkflow['nodes'] ?? [])
            ->where('type', 'n8n-nodes-base.webhook')
            ->map(fn ($node) => [
                'name' => $node['name'] ?? '',
                'path' => $node['parameters']['path'] ?? '',
                'full_url' => rtrim($n8nHost, '/') . '/webhook/' . ($node['parameters']['path'] ?? ''),
            ])
            ->values();

        return [
            'mensagem' => 'Workflow duplicado e ativado com sucesso!',
            'workflow' => $novoWorkflow,
            'webhooks' => $webhooks,
        ];
    }

    protected function criarUserPropietario(Organization $organization): void
    {
        //criar usuario para a organizacao
        $user = User::create([
            'name' => 'admin-' . $organization->name,
            'email' => 'admin-' . preg_replace('/[^a-z0-9]/', '', strtolower(trim($organization->name))) . '@zaptend.online',
            'password' => Hash::make('#zaptend123')
        ]);

        //model has role
        DB::table('model_has_roles')->insert([
            'role_id' => $organization->roles->where('name', 'Propietario')->first()->id,
            'model_id' => $user->id,
            'model_type' => 'App\Models\User',
            'organization_id' => $organization->id
        ]);

        DB::table('organization_user')->insert([
            'organization_id' => $organization->id,
            'user_id' => $user->id
        ]);
    }

    /**
     * Handle the Cliente "updated" event.
     */
    public function updated(Organization $organization): void
    {
        //
    }

    /**
     * Handle the Cliente "deleted" event.
     */
    public function deleted(Organization $organization): void
    {
        //
    }

    /**
     * Handle the Cliente "restored" event.
     */
    public function restored(Organization $organization): void
    {
        //
    }

    /**
     * Handle the Cliente "force deleted" event.
     */
    public function forceDeleted(Organization $organization): void
    {
        //
    }
}
