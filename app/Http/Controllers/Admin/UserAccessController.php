<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        return response()->json([
            'status' => 'success',
            'message' => __('Konfigurasi hak akses untuk :user berhasil disimpan.', ['user' => $user->username]),
            'permissions' => $validated['permissions'],
        ]);
    }

    /**
     * Reset permissions for the selected user to their default state.
     */
    public function reset(User $user): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => __('Hak akses untuk :user telah direset ke pengaturan awal.', ['user' => $user->username]),
            'permissions' => $this->defaultPermissions(),
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
        $template = $this->samplePermissions()[strtoupper($user->username)] ?? [];

        foreach ($template as $key => $settings) {
            if (! isset($permissions[$key])) {
                continue;
            }

            if (array_key_exists('allowed', $settings)) {
                $permissions[$key]['allowed'] = (bool) $settings['allowed'];
            }

            if (! empty($settings['actions']) && is_array($settings['actions'])) {
                foreach ($settings['actions'] as $action => $allowed) {
                    if (isset($permissions[$key]['actions'][$action])) {
                        $permissions[$key]['actions'][$action] = (bool) $allowed;
                    }
                }
            }
        }

        return $permissions;
    }

    /**
     * Sample templates to simulate permission states for existing users.
     */
    private function samplePermissions(): array
    {
        return [
            'ADMIN' => [
                'dashboard' => ['allowed' => true],
                'data-master' => ['allowed' => true],
                'master-user' => [
                    'allowed' => true,
                    'actions' => ['create' => true, 'read' => true, 'update' => true, 'delete' => false],
                ],
                'master-group' => [
                    'allowed' => true,
                    'actions' => ['create' => true, 'read' => true, 'update' => false, 'delete' => false],
                ],
                'master-jenis' => [
                    'allowed' => true,
                    'actions' => ['create' => false, 'read' => true, 'update' => false, 'delete' => false],
                ],
                'transaksi' => ['allowed' => true],
                'pengajuan' => [
                    'allowed' => true,
                    'actions' => ['create' => true, 'read' => true, 'update' => true, 'delete' => false],
                ],
                'pencairan' => [
                    'allowed' => true,
                    'actions' => ['create' => false, 'read' => true, 'update' => false, 'delete' => false],
                ],
                'laporan' => ['allowed' => true],
                'laporan-harian' => [
                    'allowed' => true,
                    'actions' => ['read' => true, 'export' => true],
                ],
                'laporan-bulanan' => [
                    'allowed' => true,
                    'actions' => ['read' => true, 'export' => false],
                ],
            ],
            'ADMIN2' => [
                'dashboard' => ['allowed' => true],
                'data-master' => ['allowed' => true],
                'master-user' => [
                    'allowed' => true,
                    'actions' => ['create' => false, 'read' => true, 'update' => true, 'delete' => false],
                ],
                'transaksi' => ['allowed' => true],
                'pengajuan' => [
                    'allowed' => true,
                    'actions' => ['create' => true, 'read' => true, 'update' => true, 'delete' => true],
                ],
                'laporan' => ['allowed' => true],
                'laporan-harian' => [
                    'allowed' => true,
                    'actions' => ['read' => true, 'export' => false],
                ],
            ],
        ];
    }
}
