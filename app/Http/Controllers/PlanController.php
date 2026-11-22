<?php

namespace App\Http\Controllers;

use App\Http\Requests\Plan\IndexRequest;
use App\Models\Plan;
use App\Policies\PlanPolicy;
use App\UseCases\Plan\IndexAction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request, IndexAction $action): View
    {
        $this->authorize('viewAny', Plan::class);

        $validated = $request->validated();

        $result = $action($request->user(), $validated);

        return view('plan.management.index', [
            'plans' => $result['plans'],
            'keyword' => $result['keyword'],
            'status' => $result['status'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
