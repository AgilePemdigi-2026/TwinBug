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
            'environment' => ['nullable', 'string', 'in:production,staging,testing,local'],
            'steps_to_reproduce' => ['nullable', 'string', 'max:5000'],
            'expected_result' => ['nullable', 'string', 'max:5000'],
            'actual_result' => ['nullable', 'string', 'max:5000'],
            'url' => ['nullable', 'string', 'max:1000'],
            'device_metadata' => ['nullable', 'array'],
            'device_metadata.*' => ['nullable', 'string', 'max:500'],
            'screenshot' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
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
            'title.required' => 'Judul bug wajib diisi.',
            'title.min' => 'Judul bug harus minimal :min karakter.',
            'title.max' => 'Judul bug tidak boleh lebih dari :max karakter.',
            'description.required' => 'Deskripsi bug wajib diisi.',
            'description.min' => 'Deskripsi bug harus minimal :min karakter.',
            'severity.required' => 'Tingkat keparahan wajib dipilih.',
            'environment.in' => 'Environment harus bernilai production, staging, testing, atau local.',
            'screenshot.mimes' => 'Screenshot harus berformat PNG, JPG, atau GIF.',
            'screenshot.max' => 'Ukuran screenshot tidak boleh melebihi 5MB.',
        ];
    }
}
