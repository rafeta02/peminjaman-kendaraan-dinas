<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySopirRequest;
use App\Http\Requests\StoreSopirRequest;
use App\Http\Requests\UpdateSopirRequest;
use App\Models\Sopir;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SopirController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('sopir_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Sopir::query()->select(sprintf('%s.*', (new Sopir)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'sopir_show';
                $editGate      = 'sopir_edit';
                $deleteGate    = 'sopir_delete';
                $crudRoutePart = 'sopirs';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('nip', function ($row) {
                return $row->nip ? $row->nip : '';
            });
            $table->editColumn('nama', function ($row) {
                return $row->nama ? $row->nama : '';
            });
            $table->editColumn('no_wa', function ($row) {
                return $row->no_wa ? $row->no_wa : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.sopirs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('sopir_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sopirs.create');
    }

    public function store(StoreSopirRequest $request)
    {
        $sopir = Sopir::create($request->all());

        return redirect()->route('admin.sopirs.index');
    }

    public function edit(Sopir $sopir)
    {
        abort_if(Gate::denies('sopir_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sopirs.edit', compact('sopir'));
    }

    public function update(UpdateSopirRequest $request, Sopir $sopir)
    {
        $sopir->update($request->all());

        return redirect()->route('admin.sopirs.index');
    }

    public function show(Sopir $sopir)
    {
        abort_if(Gate::denies('sopir_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sopirs.show', compact('sopir'));
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
