<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CekAntrianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $nik = $this->input('nik');
        $hp = $this->input('hp');

        $this->merge([
            'nik' => is_string($nik) ? preg_replace('/[^0-9A-Za-z]+/', '', $nik) : $nik,
            'hp' => is_string($hp) ? preg_replace('/[^0-9A-Za-z]+/', '', $hp) : $hp,
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'nik' => ['required', 'digits:16'],
            'hp' => ['required', 'regex:/^(?:0|62)8[1-9][0-9]{6,11}$/'],
        ];
    }
}
