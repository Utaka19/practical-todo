<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 登録
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return response()->json(['user' => $user], 201);
});

// ログイン
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (! Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Invalid login'], 401);
    }

    $request->session()->regenerate();

    return response()->json(['message' => 'Logged in'], 200);
});

// ログアウト
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['message' => 'Logged out'], 200);
});

// ログイン中ユーザー取得
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/tasks', function (Request $request) {
    return Task::where('user_id', $request->user()->id)->paginate(10);
})->middleware('auth:sanctum');

Route::post('/tasks', function (Request $request) {

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'body' => 'nullable|string',
        'status' => 'required|in:todo,in_progress,done',
        'due_date' => 'nullable|date',
    ]);

    $task = Task::create([
        ...$validated,
        'user_id' => $request->user()->id,
    ]);

    return response()->json($task, 201);
})->middleware('auth:sanctum');

Route::get('/tasks/{id}', function (Request $request, $id) {
    return Task::where('user_id', $request->user()->id)->findOrFail($id);
})->middleware('auth:sanctum');

Route::put('/tasks/{id}', function (Request $request, $id) {
    $task = Task::where('user_id', $request->user()->id)->findOrFail($id);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'body' => 'nullable|string',
        'status' => 'required|in:todo,in_progress,done',
        'due_date' => 'nullable|date',
    ]);

    $task->update($validated);

    return response()->json($task);
})->middleware('auth:sanctum');

Route::delete('/tasks/{id}', function (Request $request, $id) {
    $task = Task::where('user_id', $request->user()->id)->findOrFail($id);
    $task->delete();

    return response()->json(['message' => 'deleted']);
})->middleware('auth:sanctum');
