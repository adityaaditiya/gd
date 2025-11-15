<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMenuPermission;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserAccessController extends Controller
{
    /**
     * Display the user access management page.
     */
    public function index(): View
    {
        $users = User::orderBy('username')->orderBy('name')->get(['id', 'name', 'username', 'role']);

        return view('admin.access.index', [
            'users' => $users,
            'menuTree' => $this->menuTree(),
            'defaultPermissions' => $this->defaultPermissions(),
        ]);
    }

    /**
     * Retrieve permissions for the selected user.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
            ],
            'permissions' => $this->permissionsForUser($user),
        ]);
    }

    /**
     * Persist permissions for the selected user.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'permissions' => ['required', 'array'],
        ]);

        $normalized = $this->normalizePermissions($validated['permissions']);

        DB::transaction(function () use ($user, $normalized) {
            UserMenuPermission::query()
                ->where('user_id', $user->id)
                ->delete();

            $timestamp = now();
            $payload = collect($normalized)
                ->map(fn ($config, $menuKey) => [
                    'user_id' => $user->id,
                    'menu_key' => $menuKey,
                    'allowed' => $config['allowed'],
                    'actions' => $config['actions'],
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ])
                ->values()
                ->all();

            if ($payload !== []) {
                UserMenuPermission::query()->insert($payload);
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => __('Konfigurasi hak akses untuk :user berhasil disimpan.', ['user' => $user->username]),
            'permissions' => $normalized,
        ]);
    }

    /**
     * Reset permissions for the selected user to their default state.
     */
    public function reset(User $user): JsonResponse
    {
        UserMenuPermission::query()
            ->where('user_id', $user->id)
            ->delete();

        $defaults = $this->defaultPermissions();

        return response()->json([
            'status' => 'success',
            'message' => __('Hak akses untuk :user telah direset ke pengaturan awal.', ['user' => $user->username]),
            'permissions' => $defaults,
        ]);
    }

    /**
     * Build the application menu tree structure.
     */
    private function menuTree(): array
    {
        return [
            [
                'key' => 'dashboard',
                'label' => 'Dashboard',
                'children' => [],
                'actions' => [],
            ],
            [
                'key' => 'data-master',
                'label' => 'Data Master',
                'children' => [
                    [
                        'key' => 'master-user',
                        'label' => 'Master User',
                        'children' => [],
                        'actions' => ['create', 'read', 'update', 'delete'],
                    ],
                    [
                        'key' => 'master-kode-group',
                        'label' => 'Master Kode Group',
                        'children' => [],
                        'actions' => ['create', 'read', 'update', 'delete'],
                    ],
                    [
                        'key' => 'master-group',
                        'label' => 'Master Group',
                        'children' => [],
                        'actions' => ['create', 'read', 'update', 'delete'],
                    ],
                    [
                        'key' => 'master-jenis',
                        'label' => 'Master Jenis',
                        'children' => [],
                        'actions' => ['create', 'read', 'update', 'delete'],
                    ],
                ],
                'actions' => [],
            ],
            [
                'key' => 'transaksi',
                'label' => 'Transaksi',
                'children' => [
                    [
                        'key' => 'pengajuan',
                        'label' => 'Pengajuan',
                        'children' => [],
                        'actions' => ['create', 'read', 'update', 'delete'],
                    ],
                    [
                        'key' => 'pencairan',
                        'label' => 'Pencairan',
                        'children' => [],
                        'actions' => ['create', 'read', 'update', 'delete'],
                    ],
                ],
                'actions' => [],
            ],
            [
                'key' => 'laporan',
                'label' => 'Laporan',
                'children' => [
                    [
                        'key' => 'laporan-harian',
                        'label' => 'Laporan Harian',
                        'children' => [],
                        'actions' => ['read', 'export'],
                    ],
                    [
                        'key' => 'laporan-bulanan',
                        'label' => 'Laporan Bulanan',
                        'children' => [],
                        'actions' => ['read', 'export'],
                    ],
                ],
                'actions' => [],
            ],
        ];
    }

    /**
     * Generate an associative array with the default (all disabled) permissions.
     */
    private function defaultPermissions(): array
    {
        $defaults = [];
        $this->populateDefaultPermissions($this->menuTree(), $defaults);

        return $defaults;
    }

    private function populateDefaultPermissions(array $nodes, array &$defaults): void
    {
        foreach ($nodes as $node) {
            $actions = [];

            foreach ($node['actions'] ?? [] as $action) {
                $actions[$action] = false;
            }

            $defaults[$node['key']] = [
                'allowed' => false,
                'actions' => $actions,
            ];

            if (! empty($node['children'])) {
                $this->populateDefaultPermissions($node['children'], $defaults);
            }
        }
    }

    /**
     * Resolve permissions for the provided user based on sample templates.
     */
    private function permissionsForUser(User $user): array
    {
        $permissions = $this->defaultPermissions();

        $saved = UserMenuPermission::query()
            ->where('user_id', $user->id)
            ->get(['menu_key', 'allowed', 'actions']);

        foreach ($saved as $entry) {
            if (! isset($permissions[$entry->menu_key])) {
                continue;
            }

            $permissions[$entry->menu_key]['allowed'] = (bool) $entry->allowed;

            $storedActions = (array) ($entry->actions ?? []);

            foreach (array_keys($permissions[$entry->menu_key]['actions']) as $action) {
                $permissions[$entry->menu_key]['actions'][$action] = (bool) ($storedActions[$action] ?? false);
            }
        }

        return $permissions;
    }

    /**
     * Normalize the incoming payload so it matches the allowed menu structure.
     */
    private function normalizePermissions(array $input): array
    {
        $defaults = $this->defaultPermissions();
        $normalized = [];

        foreach ($defaults as $menuKey => $config) {
            $allowed = Arr::get($input, "$menuKey.allowed", false);
            $actions = [];

            foreach (array_keys($config['actions']) as $action) {
                $actions[$action] = (bool) Arr::get($input, "$menuKey.actions.$action", false);
            }

            $normalized[$menuKey] = [
                'allowed' => (bool) $allowed,
                'actions' => $actions,
            ];
        }

        return $normalized;
    }
}
