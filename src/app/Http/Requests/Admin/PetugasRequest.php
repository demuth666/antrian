<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PetugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Admin;
    }

    protected function prepareForValidation(): void
    {
        $nama = $this->input('name');
        $email = $this->input('email');
        $loketId = $this->input('loket_id');
        $password = $this->input('password');

        $this->merge([
            'name' => is_string($nama) ? trim($nama) : $nama,
            'email' => is_string($email) ? Str::lower(trim($email)) : $email,
            'loket_id' => $loketId === '' ? null : $loketId,
            'aktif' => $this->boolean('aktif'),
            'password' => is_string($password) && $password === '' ? null : $password,
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $petugas = $this->route('petugas');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($petugas?->id),
            ],
            'loket_id' => ['nullable', 'integer', 'exists:lokets,id'],
            'aktif' => ['required', 'boolean'],
            'password' => [
                $petugas ? 'nullable' : 'required',
                'string',
                'min:8',
            ],
        ];
    }
}
