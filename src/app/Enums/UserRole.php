<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Petugas = 'petugas';

    public function label(): string
    {
        return __('enums.roles.'.$this->value);
    }
}
