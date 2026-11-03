<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserRole;
use App\Models\Application;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isDeveloper = $this->input('role') === UserRole::Developer->value;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'role' => ['required', Rule::enum(UserRole::class)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'application_name' => $isDeveloper
                ? ['required', 'string', 'max:255', 'unique:applications,name']
                : ['nullable'],
            'application_join' => $isDeveloper
                ? ['nullable']
                : [
                    'required',
                    'string',
                    'max:255',
                    function (string $attribute, mixed $value, Closure $fail): void {
                        if (! is_string($value) || ! $this->applicationExists($value)) {
                            $fail('Aplikasi tidak ditemukan. Periksa kembali nama atau kode dari developer.');
                        }
                    },
                ],
            'terms' => ['accepted'],
        ];
    }

    private function applicationExists(string $value): bool
    {
        return Application::where('name', $value)
            ->orWhere('code', strtoupper($value))
            ->exists();
    }
}
