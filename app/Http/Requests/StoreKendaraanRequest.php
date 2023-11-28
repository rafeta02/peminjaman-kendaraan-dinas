<?php

namespace App\Http\Requests;

use App\Models\Kendaraan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreKendaraanRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('kendaraan_create');
    }

    public function rules()
    {
        return [
            'plat_no' => [
                'string',
                'required',
                'unique:kendaraans',
            ],
            'type' => [
                'string',
                'required',
            ],
            'capacity' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'gallery' => [
                'array',
            ],
        ];
    }
}
