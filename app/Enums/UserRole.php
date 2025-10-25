<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Supervisor = 'supervisor';
    case Analista = 'analista';
    case Promotor = 'promotor';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Supervisor => 'Supervisor',
            self::Analista => 'Analista',
            self::Promotor => 'Promotor',
        };
    }
}
