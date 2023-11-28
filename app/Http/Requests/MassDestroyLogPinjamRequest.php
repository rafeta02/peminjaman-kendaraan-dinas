<?php

namespace App\Http\Requests;

use App\Models\LogPinjam;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyLogPinjamRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('log_pinjam_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:log_pinjams,id',
        ];
    }
}
