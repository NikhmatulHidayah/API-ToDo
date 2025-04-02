<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class api_controller extends Controller
{

    public function store(Request $request)
    {

        $id = DB::table('todos')->insertGetId([
            'activity' => $request->input('activity'),
            'date_deadline' => $request->input('date_deadline'),
            'is_done' => $request->input('is_done', false),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Todo created successfully',
            'id_todo' => $id
        ], 201);
    }

    public function edit(Request $request, $id)
    {
        $todo = DB::table('todos')->where('id', $id)->first();
        
        if (!$todo) {
            return response()->json([
                'message' => 'Todo not found'
            ], 404);
        }

        DB::table('todos')->where('id', $id)->update([
            'activity' => $request->input('activity', $todo->activity),
            'date_deadline' => $request->input('date_deadline', $todo->date_deadline),
            'is_done' => $request->input('is_done', $todo->is_done),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Todo updated successfully'
        ], 200);
    }

    public function all()
    {
        $todos = DB::table('todos')->get();

        return response()->json($todos, 200);
    }

    public function delete($id)
    {
        $todo = DB::table('todos')->where('id', $id)->first();
        
        if (!$todo) {
            return response()->json([
                'message' => 'Todo not found'
            ], 404);
        }
        
        DB::table('todos')->where('id', $id)->delete();
        
        return response()->json([
            'message' => 'Todo deleted successfully'
        ], 200);
    }

}
