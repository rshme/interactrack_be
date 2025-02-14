<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
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
    public function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'invoice_number' => 'required|string|unique:sales,invoice_number,' . ($this->route('sale') ? $this->route('sale')->id : 'NULL') . ',id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:draft,sent,paid,overdue,cancelled',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string',
        ];
    }

    // protected function prepareForValidation()
    // {
    //     if ($this->has('items')) {
    //         $items = collect($this->items)->map(function ($item) {
    //             $item['subtotal'] = $item['quantity'] * $item['unit_price'];
    //             return $item;
    //         });

    //         $this->merge([
    //             'items' => $items->toArray(),
    //             'amount' => $items->sum('subtotal'),
    //             'tax' => $items->sum('subtotal') * 0.1, // 10% tax
    //         ]);
    //     }
    // }
}
