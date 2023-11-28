<?php

namespace App\Http\Requests;

use App\Models\Pinjam;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePinjamRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('pinjam_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'nullable',
            ],
            'no_wa' => [
                'string',
                'required',
            ],
            'kendaraan_id' => [
                'required',
                'integer',
            ],
            'date_start' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'date_end' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'reason' => [
                'string',
                'required',
            ],
            'laporan_kegiatan' => [
                'array',
            ],
            'foto_kegiatan' => [
                'array',
            ],
        ];
    }
}
