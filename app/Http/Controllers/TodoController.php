<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        $todos = Todo::with('user')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Daftar todo milik user ' . Auth::user()->name,
            'todos' => $todos,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'todo' => ['required', 'max:255'],
            ],
            [
                'todo.required' => 'Todo Wajib Anda Isi!',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = $request->user()->id;

        $todo = Todo::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Todo Berhasil Dibuat!',
            'todo' => $todo,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json([
                'status' => false,
                'message' => 'Todo tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Todo Ditemukan',
            'todo' => $todo,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $todo = Todo::find($id);

        if ($todo->user_id !== $request->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses ke todo ini.',
            ], 403);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'todo' => ['required', 'max:255'],
            ],
            [
                'todo.required' => 'Todo Wajib Anda Isi!',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $todo->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Todo Berhasil Diperbarui!',
            'todo' => $todo,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json([
                'status' => false,
                'message' => 'Data buku tidak ditemukan.',
            ], 404);
        }

        // Simpan data sebelum dihapus (optional)
        $deletedTodo = $todo;

        // Hapus data
        $todo->delete();

        // Response sukses
        return response()->json([
            'status' => true,
            'message' => 'Data Todo berhasil dihapus.',
            'todo' => $deletedTodo,
        ], 200);
    }
}
