<?php

namespace App\Http\Requests;

use App\Enums\BugSeverity;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreBugReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->filled('severity')) {
            $this->merge(['severity' => BugSeverity::Medium->value]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'module' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'min:20', 'max:10000'],
            'severity' => ['required', new Enum(BugSeverity::class)],
            'screenshot' => ['nullable', 'file', 'mimes:jpeg,jpg,png', 'max:5120'],
        ];
    }

    /**
     * Get custom validation messages in Indonesian.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Laporan gagal dikirim. Judul dan deskripsi harus diisi!',
            'title.min' => 'Judul bug harus minimal :min karakter.',
            'title.max' => 'Judul bug tidak boleh lebih dari :max karakter.',
            'description.required' => 'Laporan gagal dikirim. Judul dan deskripsi harus diisi!',
            'description.min' => 'Deskripsi bug harus minimal :min karakter.',
            'severity.required' => 'Tingkat keparahan wajib dipilih.',
            'screenshot.mimes' => 'Laporan gagal dikirim. Format file tidak didukung. Gunakan JPG atau PNG.',
            'screenshot.max' => 'Ukuran screenshot tidak boleh melebihi 5MB.',
        ];
    }
}
