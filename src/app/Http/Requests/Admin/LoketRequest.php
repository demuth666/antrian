<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class LoketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Admin;
    }

    protected function prepareForValidation(): void
    {
        $nama = $this->input('nama');
        $layanan = $this->input('layanan');

        $this->merge([
            'nama' => is_string($nama) ? trim($nama) : $nama,
            'aktif' => $this->boolean('aktif'),
            'layanan' => is_array($layanan)
                ? array_values(array_unique(array_map('intval', $layanan)))
                : [],
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'aktif' => ['required', 'boolean'],
            'layanan' => ['array'],
            'layanan.*' => ['integer', 'exists:layanans,id'],
        ];
    }
}
