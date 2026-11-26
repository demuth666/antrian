<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BantuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Admin;
    }

    protected function prepareForValidation(): void
    {
        $kode = $this->input('kode');
        $nama = $this->input('nama');

        $this->merge([
            'kode' => is_string($kode) ? Str::slug(trim($kode), '_') : $kode,
            'nama' => is_string($nama) ? trim($nama) : $nama,
            'aktif' => $this->boolean('aktif'),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $bantuan = $this->route('bantuan');

        return [
            'kode' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('bantuans', 'kode')->ignore($bantuan?->id),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'aktif' => ['required', 'boolean'],
        ];
    }
}
