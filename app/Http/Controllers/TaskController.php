<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TaskList;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\MoveTaskRequest;
use App\Http\Requests\UpdateTaskCompletionRequest;

class TaskController extends Controller
{

    public function store(StoreTaskRequest $request, int $list): RedirectResponse
    {
        $taskList = $this->findOwnedList($request, $list);

        // Determine the next position for the new task
        $position = ($taskList->tasks()->max('position') ?? -1) + 1;

        $taskList->tasks()->create([
            ...$request->validated(),
            'position' => $position,
        ]);

        return to_route('task-lists.show', ['list' => $taskList->id]);
    }

    public function move(MoveTaskRequest $request, int $list, int $task): RedirectResponse 
    {
        $taskList = $this->findOwnedList($request, $list);

        $validated = $request->validated();

        // Commit database changes together if successfull (A task might already be at the top or 
        // bottom of the list, in which case we don't want to make any changes)
        DB::transaction(function () use ($taskList, $task, $validated) {

            // Get tasks in display order       
            $tasks = $taskList->tasks()
                ->orderBy('position')
                ->orderBy('id')
                ->get();

            // Find the index of the task to move
            $currentIndex = $tasks->search(
                fn ($item) => $item->id === $task
            );

            // If the task is not found, we don't want to make any changes
            if($currentIndex === false) {
                return;
            }

            // Calulate the target index
            $targetIndex = $currentIndex
                + ($validated['direction'] === 'up' ? -1 : 1);

            if ($targetIndex < 0 || $targetIndex >= $tasks->count()) {
                return;
            }

            $orderedTasks = $tasks->all();

            // Swap the positions of the current task and the target task
            [$orderedTasks[$currentIndex], $orderedTasks[$targetIndex]] = [
                $orderedTasks[$targetIndex],
                $orderedTasks[$currentIndex],
            ];

            // Persist the new positions
            foreach ($orderedTasks as $position => $item) {
                $item->update(['position' => $position]);
            }
        });

        return to_route('task-lists.show', ['list' => $taskList->id]);
    }

    public function updateCompletion(UpdateTaskCompletionRequest $request, int $list, int $task): RedirectResponse 
    {
        $taskList = $this->findOwnedList($request, $list);

        $taskItem = $taskList->tasks()->findOrFail($task);

        $taskItem->update($request->validated());

        return to_route('task-lists.show', ['list' => $taskList->id]);
    }

    public function destroy(Request $request, int $list, int $task): RedirectResponse 
    {
        $taskList = $this->findOwnedList($request, $list);

        $taskList->tasks()
            ->findOrFail($task)
            ->delete();

        return to_route('task-lists.show', ['list' => $taskList->id]);
    }

    private function findOwnedList(Request $request, int $listId): TaskList
    {
        return $request->user()
            ->taskLists()
            ->findOrFail($listId);
    }
}