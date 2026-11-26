<?php

namespace App\Enums;

enum KategoriAntrian: string
{
    case Disabilitas = 'disabilitas';
    case Lansia = 'lansia';
    case IbuHamil = 'ibu_hamil';
    case RentanLain = 'rentan_lain';

    public function label(): string
    {
        return __('enums.categories.'.$this->value);
    }
}
