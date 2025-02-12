<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function add(Request $request)
    {
        $validateData = $request->validate([
            "title" => "string|required|max:100",
            "description" => "string|required|min:100"
        ]);

        $user = $request->user();
        $task = $user->createdTasks()->create($validateData);
        $task->users()->attach($user->id);

        return response()->json([
            "success" => true,
            "message" => "Success",
            "name" => $validateData["title"],
        ]);
    }
    public function update(string $id, Request $request)
    {
        $validateData = $request->validate(["title" => "required|string|max:100"]);
        $task = Task::find($id);

        $task->update(["title" => $validateData["title"]]);
        return response()->json(["message" => $task]);
    }
    public function delete(string $id)
    {
        $task->users()->detach();

        // Удаляем сам task
        $task->delete();

        return response()->json([
            'message' => 'Task удалена успешно!'
        ]);
    }
}
