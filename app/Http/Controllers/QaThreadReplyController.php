<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Reply\StoreRequest;
use App\Http\Requests\Reply\UpdateRequest;
use App\Models\QaThread;
use App\Models\QaReply;
use App\UseCases\ThreadReply\StoreAction;
use App\UseCases\ThreadReply\UpdateAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QaThreadReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(QaThread $thread, StoreRequest $request, StoreAction $action): RedirectResponse
    {
        $reply = $action($thread, $request->validated());

        return redirect()
            ->route('qa-board.show', $thread)
            ->with('success', 'リプライを作成しました。');
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
    public function edit(QaThread $thread, QaReply $reply): view
    {
        $this->authorize('update', $reply);

        return view('qa-thread.reply-edit', [
            'thread' => $thread,
            'reply' => $reply
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QaThread $thread, QaReply $reply, UpdateRequest $request, UpdateAction $action)
    {
        $action($reply,$request->validated());

        return redirect()
            ->route('qa-board.show', [
                'thread' => $thread,
                'reply' =>  $reply,
            ])
            ->with('success', 'スレッドを更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
