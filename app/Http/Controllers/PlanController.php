<?php

namespace App\Http\Controllers;

use App\Http\Requests\Plan\IndexRequest;
use App\Http\Requests\Plan\StoreRequest;
use App\Http\Requests\Plan\UpdateRequest;
use App\Models\Plan;
use App\Policies\PlanPolicy;
use App\UseCases\Plan\IndexAction;
use App\UseCases\Plan\StoreAction;
use App\UseCases\Plan\UpdateAction;
use App\UseCases\Plan\DestroyAction;
use App\UseCases\Plan\PublishAction;
use App\UseCases\Plan\ArchiveAction;
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
    public function create(): View
    {
        $this->authorize('create', Plan::class);

        return view('plan.management.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, StoreAction $action): RedirectResponse
    {
        $plan = $action($request->user(), $request->validated());

        return redirect()
            -> route('admin.plans.show',$plan)
            -> with('success', "$plan->name を作成しました。");
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan): View
    {
        $this->authorize('view', $plan);

        return view('plan.management.show', [
            'plan' => $plan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan): View
    {
        $this->authorize('update', $plan);

        return view('plan.management.edit', [
            'plan' => $plan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Plan $plan, UpdateRequest $request, UpdateAction $action): RedirectResponse
    {
        $action($plan,$request->validated());

        return redirect()
            ->route('admin.plans.update', $plan)
            ->with('success', "$plan->name へ更新しました。");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan, DestroyAction $action): RedirectResponse
    {
        $this->authorize('delete', $plan);

        try {
            $action($plan);

            return redirect()
                ->route('admin.plans.index')
                ->with('success', "$plan->name を削除しました。");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.plans.index')
                ->with('error', "$plan->name を削除できません。");
        }
    }

    public function publish(Plan $plan, PublishAction $action): RedirectResponse
    {
        $this->authorize('publish', $plan);

        try{
            $action($plan, request()->user());

            return redirect()
                ->route('admin.plans.show', $plan)
                ->with('success', "$plan->name を公開しました。");
        } catch (Exception $e){
            return redirect()
                ->route('admin.plans.index')
                ->with('error', "$plan->name の状態を変更できません。");
        }
    }

    public function archive(Plan $plan, ArchiveAction $action): RedirectResponse
    {
        $this->authorize('archive', $plan);

        try{
            $action($plan, request()->user());

            return redirect()
                ->route('admin.plans.show', $plan)
                ->with('success', "$plan->name をアーカイブにしました。");
        } catch (Exception $e){
            return redirect()
                ->route('admin.plans.index')
                ->with('error', "$plan->name の状態を変更できません。");
        }
    }
}


