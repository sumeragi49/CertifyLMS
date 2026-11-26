<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notification\IndexRequest;
use App\Models\InfoNotification;
use App\UseCases\Notification\IndexAction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function index(IndexRequest $request, IndexAction $action): View
    {
        $this->authorize('viewAny', InfoNotification::class);

        $validated = $request->validated();

        $result = $action($request->user(), $validated);

        $validated['tab'] = $request->input('tab');

        return view('notifications.index', [
            'notifications' => $result['notifications'],
            'unreadCount' => $result['unreadCount'],
            'tabs' => $result['tabs'],
            'tab' => $validated['tab'] ?? 'all',
        ]);
    }
}
