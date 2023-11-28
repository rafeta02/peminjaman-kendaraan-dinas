<?php

namespace App\Http\Requests;

use App\Models\LogPinjam;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLogPinjamRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('log_pinjam_edit');
    }

    public function rules()
    {
        return [];
    }
}
