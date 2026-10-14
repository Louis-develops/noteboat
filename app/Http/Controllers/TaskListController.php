<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\StoreTaskListRequest;

class TaskListController extends Controller
{
    public function index(Request $request): Response
    {
        $taskLists = $request->user()
            ->taskLists()
            ->withCount('tasks')
            // Order the task lists by the most recently created first
            ->latest()
            ->get();

        return Inertia::render('task-lists/Index', [
            'taskLists' => $taskLists,
        ]);
    }

    public function show(Request $request, int $list): Response
    {
        $taskList = $request->user()
            ->taskLists()
            ->with([
                'tasks' => fn ($query) => $query
                    ->orderBy('position')
                    // Order by id as a tie-breaker incase any tasks have the same position
                    ->orderBy('id'),
            ])
            ->findOrFail($list);

        return Inertia::render('task-lists/Show', [
            'taskList' => $taskList,
        ]);
    }

    public function store(StoreTaskListRequest $request): RedirectResponse
    {
        $request->user()->taskLists()->create($request->validated());

        return to_route('task-lists.index');
    }
}