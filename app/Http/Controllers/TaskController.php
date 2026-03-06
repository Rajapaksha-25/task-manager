<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::where('user_id', auth()->id())
            ->when($request->status,   fn($q) => $q->where('status',   $request->status))
            ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
            ->when($request->search,   fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->orderBy($request->sort_by  ?? 'created_at', $request->sort_dir ?? 'desc')
            ->paginate($request->per_page ?? 10);

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'status'   => 'nullable|in:pending,in_progress,completed',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create([
            ...$request->only('title', 'description', 'status', 'priority', 'due_date'),
            'user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'Task created.', 'data' => $task], 201);
    }

    public function show(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);
        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);

        $request->validate([
            'title'    => 'sometimes|string|max:255',
            'status'   => 'nullable|in:pending,in_progress,completed',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->only('title', 'description', 'status', 'priority', 'due_date'));

        return response()->json(['message' => 'Task updated.', 'data' => $task->fresh()]);
    }

    public function destroy(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);
        $task->delete();
        return response()->json(['message' => 'Task moved to trash.']);
    }

    public function trashed()
    {
        $tasks = Task::onlyTrashed()->where('user_id', auth()->id())->paginate(10);
        return response()->json($tasks);
    }

    public function restore($id)
    {
        $task = Task::onlyTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $task->restore();
        return response()->json(['message' => 'Task restored.']);
    }

    public function forceDelete($id)
    {
        $task = Task::onlyTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $task->forceDelete();
        return response()->json(['message' => 'Task permanently deleted.']);
    }
}
