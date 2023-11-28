<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySopirRequest;
use App\Http\Requests\StoreSopirRequest;
use App\Http\Requests\UpdateSopirRequest;
use App\Models\Sopir;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SopirController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('sopir_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sopirs = Sopir::all();

        return view('frontend.sopirs.index', compact('sopirs'));
    }

    public function create()
    {
        abort_if(Gate::denies('sopir_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.sopirs.create');
    }

    public function store(StoreSopirRequest $request)
    {
        $sopir = Sopir::create($request->all());

        return redirect()->route('frontend.sopirs.index');
    }

    public function edit(Sopir $sopir)
    {
        abort_if(Gate::denies('sopir_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.sopirs.edit', compact('sopir'));
    }

    public function update(UpdateSopirRequest $request, Sopir $sopir)
    {
        $sopir->update($request->all());

        return redirect()->route('frontend.sopirs.index');
    }

    public function show(Sopir $sopir)
    {
        abort_if(Gate::denies('sopir_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.sopirs.show', compact('sopir'));
    }

    public function destroy(Sopir $sopir)
    {
        abort_if(Gate::denies('sopir_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sopir->delete();

        return back();
    }

    public function massDestroy(MassDestroySopirRequest $request)
    {
        $sopirs = Sopir::find(request('ids'));

        foreach ($sopirs as $sopir) {
            $sopir->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
