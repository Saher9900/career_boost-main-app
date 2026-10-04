<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadResumeRequest extends FormRequest
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
            'resume_source' => ['required', 'string', 'in:existing,upload'],
            'existing_resume_id' => [
                'exclude_unless:resume_source,existing',
                'required_if:resume_source,existing',
                'integer',
                Rule::exists('resumes', 'id')->where('user_id', $this->user()->getAuthIdentifier()),
            ],
            'resume' => ['required_if:resume_source,upload', 'file', 'mimes:pdf', 'max:5120'],
        ];
    }
}
