<?php

namespace App\Http\Requests;

use App\Models\Sopir;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSopirRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('sopir_edit');
    }

    public function rules()
    {
        return [
            'nip' => [
                'string',
                'required',
            ],
            'nama' => [
                'string',
                'required',
            ],
            'no_wa' => [
                'string',
                'nullable',
            ],
        ];
    }
}
