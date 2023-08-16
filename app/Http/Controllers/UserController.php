<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::all();
        return response()->json(['data' => $users], 200);
    }

    public function show($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'user not found!'], 404);
        } else {
            return response()->json($user, 200);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $user = User::where('id', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'user not found!'], 404);
        } else {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->save();

            return response()->json($user, 200);
        }
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'user not found!'], 404);
        } else {
            $user->delete();
            return response()->json(null, 204);
        }
    }
}