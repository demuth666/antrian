<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Admin;
    }

    protected function prepareForValidation(): void
    {
        $nama = $this->input('nama');
        $prefix = $this->input('prefix');

        $this->merge([
            'nama' => is_string($nama) ? trim($nama) : $nama,
            'prefix' => is_string($prefix) ? Str::upper(trim($prefix)) : $prefix,
            'aktif' => $this->boolean('aktif'),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $layanan = $this->route('layanan');

        return [
            'nama' => ['required', 'string', 'max:255'],
            'prefix' => [
                'required',
                'string',
                'size:1',
                'alpha',
                Rule::unique('layanans', 'prefix')->ignore($layanan?->id),
            ],
            'aktif' => ['required', 'boolean'],
        ];
    }
}
