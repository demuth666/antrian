<?php

namespace App\Http\Requests\Petugas;

use Illuminate\Foundation\Http\FormRequest;

class CariTiketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $nik = $this->input('nik');

        $this->merge([
            'nik' => is_string($nik) ? preg_replace('/[^0-9A-Za-z]+/', '', $nik) : $nik,
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'nik' => ['required', 'digits:16'],
        ];
    }
}
