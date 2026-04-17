<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                   => 'required|min:3|max:255',
            'country_id'             => 'required|integer',
            'vendor_type_id'         => 'required|integer',
            'website_url'            => 'nullable|url|max:255',
            'logo'                   => $this->hasFile('logo') ? 'image|max:2048' : 'nullable',
            'vendor_documents'       => 'nullable',
            'vendor_documents.*'     => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip|max:10240',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }

    /**
     * Add custom after-validation: at least one contact must have a number or email.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $raw      = $this->input('contacts');
            $contacts = is_string($raw) ? (json_decode($raw, true) ?? []) : (is_array($raw) ? $raw : []);

            // Remove completely empty rows
            $contacts = array_filter($contacts, fn($c) =>
                !empty(trim($c['name'] ?? '')) ||
                !empty(trim($c['number'] ?? '')) ||
                !empty(trim($c['email'] ?? ''))
            );

            if (empty($contacts)) {
                $validator->errors()->add('contacts', 'At least one contact person is required.');
                return;
            }

            $hasNumberOrEmail = false;
            foreach ($contacts as $contact) {
                if (!empty(trim($contact['number'] ?? '')) || !empty(trim($contact['email'] ?? ''))) {
                    $hasNumberOrEmail = true;
                    break;
                }
            }

            if (!$hasNumberOrEmail) {
                $validator->errors()->add('contacts', 'At least one contact must have a phone number or email address.');
            }
        });
    }
}
