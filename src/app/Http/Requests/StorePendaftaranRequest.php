<?php

namespace App\Http\Requests;

use App\Enums\JenisKelamin;
use App\Enums\KategoriAntrian;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePendaftaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $kategori = $this->input('kategori');
        $nik = $this->input('nik');
        $hp = $this->input('hp');
        $nama = $this->input('nama');
        $alamat = $this->input('alamat');
        $namaPendamping = $this->input('nama_pendamping');

        $this->merge([
            'nik' => is_string($nik) ? preg_replace('/[^0-9A-Za-z]+/', '', $nik) : $nik,
            'hp' => is_string($hp) ? preg_replace('/[^0-9A-Za-z]+/', '', $hp) : $hp,
            'nama' => is_string($nama) ? trim($nama) : $nama,
            'alamat' => is_string($alamat) ? trim($alamat) : $alamat,
            'nama_pendamping' => is_string($namaPendamping) && trim($namaPendamping) !== ''
                ? trim($namaPendamping)
                : null,
            'jenis_disabilitas' => $kategori === KategoriAntrian::Disabilitas->value
                ? $this->input('jenis_disabilitas')
                : null,
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'kategori' => ['required', Rule::enum(KategoriAntrian::class)],
            'jenis_disabilitas' => [
                'nullable',
                'required_if:kategori,disabilitas',
                Rule::in(array_keys(config('layanan.jenis_disabilitas'))),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16'],
            'hp' => ['required', 'regex:/^(?:0|62)8[1-9][0-9]{6,11}$/'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['required', Rule::enum(JenisKelamin::class)],
            'alamat' => ['required', 'string', 'max:255'],
            'layanan_id' => [
                'required',
                'integer',
                Rule::exists('layanans', 'id')->where('aktif', true),
            ],
            'bantuan' => ['nullable', 'array'],
            'bantuan.*' => [
                'integer',
                Rule::exists('bantuans', 'id')->where('aktif', true),
            ],
            'nama_pendamping' => ['nullable', 'string', 'max:255'],
        ];
    }
}
