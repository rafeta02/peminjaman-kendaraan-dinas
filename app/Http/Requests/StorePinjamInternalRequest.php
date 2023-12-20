<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePinjamInternalRequest extends FormRequest
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
            'kendaraan_id' => [
                'required',
                'integer',
            ],
            'date_start' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.clock_format'),
            ],
            'date_end' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.clock_format'),
            ],
            'reason' => [
                'string',
                'required',
            ],
        ];
    }
}
