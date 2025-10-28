<?php

use App\Enums\UserRole;

return [
    'brand' => [
        'name' => 'Alpe Fresh Promotoras',
        'acronym' => 'AFP',
    ],
    'items' => [
        [
            'label' => 'Inicio',
            'icon' => 'home',
            'route' => 'dashboard',
        ],
        [
            'label' => 'Nueva Visita',
            'icon' => 'zap',
            'route' => 'evaluations.create',
            'roles' => [
                UserRole::Admin,
                UserRole::Supervisor,
                UserRole::Promotor,
            ],
        ],
        [
            'label' => 'Mis Visitas',
            'icon' => 'file-text',
            'route' => 'evaluations.index',
            'roles' => [
                UserRole::Admin,
                UserRole::Supervisor,
                UserRole::Promotor,
            ],
        ],
        [
            'label' => 'Reportes',
            'icon' => 'bar-chart-3',
            'route' => 'reports.dashboard',
            'roles' => [
                UserRole::Admin,
                UserRole::Supervisor,
                UserRole::Analista,
            ],
        ],
        [
            'label' => 'Configuración',
            'icon' => 'settings',
            'roles' => [
                UserRole::Admin,
                UserRole::Supervisor,
            ],
            'children' => [
                [
                    'label' => 'Usuarios',
                    'icon' => 'users',
                    'route' => 'config.users',
                ],
                [
                    'label' => 'Cadenas',
                    'icon' => 'building-2',
                    'route' => 'config.chains',
                ],
                [
                    'label' => 'Zonas',
                    'icon' => 'map',
                    'route' => 'config.zones',
                ],
                [
                    'label' => 'Tiendas',
                    'icon' => 'store',
                    'route' => 'config.stores',
                ],
                [
                    'label' => 'Asignaciones',
                    'icon' => 'link',
                    'route' => 'config.assignments',
                ],
                [
                    'label' => 'Productos',
                    'icon' => 'package',
                    'route' => 'config.products',
                ],
                [
                    'label' => 'Campos de Evaluación',
                    'icon' => 'list-checks',
                    'route' => 'config.fields',
                ],
                [
                    'label' => 'Permisos',
                    'icon' => 'shield-check',
                    'route' => 'config.permissions',
                ],
                [
                    'label' => 'Notificaciones',
                    'icon' => 'bell',
                    'route' => 'config.notifications',
                ],
            ],
        ],
    ],
];
