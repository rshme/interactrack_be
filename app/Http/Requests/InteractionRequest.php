<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InteractionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'type' => 'required|string|in:email,call,meeting,note',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'interaction_date' => 'required|date',
            'status' => 'required|string|in:planned,completed,cancelled,follow-up-required',
            'outcome' => 'nullable|string',
            'metadata' => 'nullable|array',
            'metadata.email_subject' => 'required_if:type,email',
            'metadata.email_body' => 'required_if:type,email',
            'metadata.duration' => 'required_if:type,call,meeting',
            'metadata.location' => 'required_if:type,meeting',
        ];
    }
}
