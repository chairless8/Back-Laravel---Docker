<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
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
            // Datos del contacto
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'birthday' => 'nullable|date',
            'website' => 'nullable|url',
            'company' => 'nullable|string|max:255',

            // Teléfonos
            'phones' => 'nullable|array',
            'phones.*.id' => 'nullable|integer|exists:phones,id',
            'phones.*.phone_number' => 'required|string|max:20',
            'phones_to_delete' => 'nullable|array',
            'phones_to_delete.*' => 'integer|exists:phones,id',

            // Emails
            'emails' => 'nullable|array',
            'emails.*.id' => 'nullable|integer|exists:emails,id',
            'emails.*.email' => 'required|email|max:255',
            'emails_to_delete' => 'nullable|array',
            'emails_to_delete.*' => 'integer|exists:emails,id',

            // Direcciones
            'addresses' => 'nullable|array',
            'addresses.*.id' => 'nullable|integer|exists:addresses,id',
            'addresses.*.address_line' => 'required|string|max:255',
            'addresses.*.city' => 'nullable|string|max:100',
            'addresses.*.state' => 'nullable|string|max:100',
            'addresses.*.postal_code' => 'nullable|string|max:20',
            'addresses.*.country' => 'nullable|string|max:100',
            'addresses_to_delete' => 'nullable|array',
            'addresses_to_delete.*' => 'integer|exists:addresses,id',
        ];
    }
}
