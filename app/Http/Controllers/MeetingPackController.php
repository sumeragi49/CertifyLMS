<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\UserRole;
use App\Enums\MeetingPackStatus;
use App\Models\MeetingPack;
use App\Http\Requests\MeetingPack\IndexRequest;
use App\Http\Requests\MeetingPack\StoreRequest;
use App\Http\Requests\MeetingPack\UpdateRequest;
use App\UseCases\MeetingPack\IndexAction;
use App\UseCases\MeetingPack\StoreAction;
use App\UseCases\MeetingPack\ShowAction;
use App\UseCases\MeetingPack\UpdateAction;
use App\UseCases\MeetingPack\DestroyAction;
use App\UseCases\MeetingPack\PublishAction;
use App\UseCases\MeetingPack\ArchiveAction;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MeetingPackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request,IndexAction $action): View
    {
        $this->authorize('viewAny', MeetingPack::class);

        $validated = $request->validated();

        $result = $action($request->user(), $validated);

        return view('meeting-pack.management.index', [
            'plans' => $result['plans'],
            'keyword' => $result['keyword'],
            'status' => $result['status'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', MeetingPack::class);

        return view('meeting-pack.management.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, StoreAction $action): RedirectResponse
    {
        $plan = $action($request->user(), $request->validated());

        return redirect()
            ->route('admin.meeting-packs.show', $plan)
            ->with('success', "$plan->name を作成しました。");
    }

    /**
     * Display the specified resource.
     */
    public function show(MeetingPack $plan, ShowAction $action): View
    {
        $this->authorize('view', $plan);

        return view('meeting-pack.management.show', [
            'plan' => $plan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MeetingPack $plan): View
    {
        $this->authorize('update', $plan);

        return view('meeting-pack.management.edit', [
            'plan' => $plan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MeetingPack $plan, UpdateRequest $request, UpdateAction $action): RedirectResponse
    {
        $action($plan,$request->validated());

        return redirect()
            ->route('admin.meeting-packs.update', $plan)
            ->with('success', "$plan->name へ更新しました。");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MeetingPack $plan, DestroyAction $action): RedirectResponse
    {
        $this->authorize('delete', $plan);

        $action($plan);

        return redirect()
            ->route('admin.meeting-packs.index')
            ->with('success', "$plan->name を削除しました。");
    }

    public function publish(MeetingPack $plan, PublishAction $action): RedirectResponse
    {
        $this->authorize('publish', $plan);

        $action($plan, request()->user());

        return redirect()
            ->route('admin.meeting-packs.show', $plan)
            ->with('success', "$plan->name を公開しました。");
    }

    public function archive(MeetingPack $plan, ArchiveAction $action): RedirectResponse
    {
        $this->authorize('archive', $plan);

        $action($plan, request()->user());

        return redirect()
            ->route('admin.meeting-packs.show', $plan)
            ->with('success', "$plan->name をアーカイブにしました。");
    }
}
