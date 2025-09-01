<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks()->latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string|max:1000']);

        $task = Auth::user()->tasks()->create([
            'title' => $request->title,
            'start_time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }

    public function update(Task $task)
    {
        $this->authorize('update', $task);

        $newStatus = !$task->is_completed;

        $task->update([
            'is_completed' => $newStatus,
            'done_time' => $newStatus ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->delete();

        return response()->json(['success' => true]);
    }
}
