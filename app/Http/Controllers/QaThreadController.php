<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Enums\QaThreadStatus;
use App\Http\Requests\Thread\IndexRequest;
use App\Models\Thread;
use App\UseCases\Thread\IndexAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QaThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request,IndexAction $action): view
    {
        //$this->authorize('viewAny', [QuestionCategory::class, $certification]);

        $validated = $request->validated();

        $validated['keyword'] = $request->input('keyword');

        $validated['certification_id'] = $request->input('certification_id');

        $result = $action($request->user(), $validated);

        $segments = [
            '' => '全て',
            'unresolved' => '未解決',
            'resolved' => '解決済',
        ];

        return view('qa-thread.index', [
            'threads' => $result['threads'],
            'filters' => $result['filters'],
            'certifications' => $result['certifications'],
            'publishedStatus' => $result['status'] ?? '',
            'segments' => $segments,
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
