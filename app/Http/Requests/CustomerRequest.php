<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
        ];

        // Make email unique unless it's an update for the same customer
        if ($this->isMethod('post')) {
            $rules['email'] .= '|unique:customers';
        } else {
            $rules['email'] .= '|unique:customers,email,' . $this->customer->id;
        }

        return $rules;
    }
}
