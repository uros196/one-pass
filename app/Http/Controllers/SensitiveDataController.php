<?php

namespace App\Http\Controllers;

use App\Contracts\SensitiveData\DataFormRequest;
use App\Contracts\SensitiveData\DataModel;
use App\Contracts\SensitiveData\DataRegistrar;
use App\Contracts\SensitiveData\DataResource;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SensitiveDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DataResource $resource): Response
    {
        return Inertia::render('SET-COMPONENT-NAME', [
            'data' => $resource
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: this function maybe not needed
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DataFormRequest $request, DataRegistrar $registrar)
    {
        DB::transaction(function () use ($request, $registrar) {
            $registrar->store($request->get());
        });

        // TODO: return the response to the app
    }

    /**
     * Display the specified resource.
     */
    public function show(DataResource $resource): Response
    {
        // TODO: if is the simple data return (data shown in the modal), then remove Inertia response

        return Inertia::render('', [
            'data' => $resource
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataResource $resource): Response
    {
        // TODO: if is the simple data return (data shown in the modal), then remove Inertia response

        return Inertia::render('', [
            'data' => $resource
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DataFormRequest $request, DataRegistrar $registrar, DataModel $model)
    {
        DB::transaction(function () use ($request, $registrar, $model) {
            $registrar->update($request->get(), $model->get());
        });

        // TODO: return the response to the app
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataModel $model)
    {
        $model->get()->delete();

        // TODO: return the response to the app
    }
}
