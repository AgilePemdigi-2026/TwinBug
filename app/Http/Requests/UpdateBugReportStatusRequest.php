<?php

namespace App\Http\Requests;

use App\Enums\BugStatus;
use App\Enums\UserRole;
use App\Models\BugReport;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateBugReportStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $report = $this->route('bugReport');
        $user = $this->user();

        abort_unless(
            $report instanceof BugReport
                && $user !== null
                && $user->application_id === $report->application_id,
            404,
        );

        return $user->role === UserRole::Developer;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(BugStatus::class)],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status laporan wajib dipilih.',
            'status.enum' => 'Status laporan yang dipilih tidak valid.',
        ];
    }
}
