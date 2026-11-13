<?php

namespace App\Http\Controllers;

use App\Enums\QaThreadStatus;
use App\Http\Requests\Thread\IndexRequest;
use App\Http\Requests\Thread\StoreRequest;
use App\Models\QaThread;
use App\Models\Certification;
use App\UseCases\Thread\IndexAction;
use App\UseCases\Thread\CreateAction;
use App\UseCases\Thread\StoreAction;
use App\UseCases\Thread\ShowAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class QaThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request,IndexAction $action): view
    {
        $this->authorize('viewAny', QaThread::class);

        $validated = $request->validated();

        $validated['keyword'] = $request->input('keyword');

        $validated['certification_id'] = $request->input('certification_id');

        $result = $action($request->user(), $validated);

        return view('qa-thread.index', [
            'threads' => $result['threads'],
            'filters' => $result['filters'],
            'certifications' => $result['certifications'],
            'publishedStatus' => $result['status'] ?? '',
            'segments' => $result['segments'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Certification $certifications, CreateAction $action): View
    {
        $this->authorize('create', QaThread::class);

        return view('qa-thread.create', [
            'certifications' => $action($certifications),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, StoreAction $action): RedirectResponse
    {
        $thread = $action($request->user(), $request->validated());

        return redirect()
            ->route('qa-thread.show', $thread)
            ->with('success', '資格を作成しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(QaThread $thread, ShowAction $action)
    {
        $this->authorize('view', $thread);

        return view('qa-thread.show', [
            'thread' => $thread,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QaThread $thread): view
    {
        $this->authorize('update', $thread);

        return view('qa-thread.edit', [
            'thread' => $thread
        ]);
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
