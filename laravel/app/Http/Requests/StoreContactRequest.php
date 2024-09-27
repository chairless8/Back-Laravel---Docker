<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
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
             'website' => 'nullable|string|max:255',
             'company' => 'nullable|string|max:255',
 
             // Teléfonos
             'phones' => 'nullable|array',
             'phones.*.phone_number' => 'required|string|max:20',
 
             // Emails
             'emails' => 'nullable|array',
             'emails.*.email' => 'required|email|max:255',
 
             // Direcciones
             'addresses' => 'nullable|array',
             'addresses.*.address_line' => 'required|string|max:255',
             'addresses.*.city' => 'nullable|string|max:100',
             'addresses.*.state' => 'nullable|string|max:100',
             'addresses.*.postal_code' => 'nullable|string|max:20',
             'addresses.*.country' => 'nullable|string|max:100',
        ];
    }
}
