<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

  /**
   * Login user
   * Store token in database with sanctum
   */
  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'username' => 'string|required',
      'password' => 'string|required'
    ]);

    if ($validator->fails() || !(Auth::attempt($request->all()))) {
      return response()->json(['message' => 'Login invalid'], 422);
    }

    $user = auth()->user();
    $token = $user->createToken('auth')->plainTextToken;

    User::where(['id' => $user->id])->update(['remember_token' => $token]);

    return response()->json(['message' => 'Login success', 'token' => $token, 'user' => $user], 200);
  }

  /**
   * Register user
   * Store new user in database
   */
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'username' => 'string|required',
      'email' => 'string|required',
      'password' => 'string|required'
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => 'Invalid input'], 422);
    }

    $pict = $request->file('profile');
    if ($pict) {
      $pict_url = $pict->store('uploads');
      $pict->move(public_path('uploads'), $pict_url);
    }

    $dupeUsername = User::where(['username' => $request->username])->first();

    $user = new User();
    $user->name = $request->name;
    if ($dupeUsername) return response()->json(['message' => 'Username already exist'], 422);

    $user->username = $request->username;
    $user->email = $request->email;
    $user->bio = $request->bio;

    if ($pict) {
      $user->profile = $pict_url;
    }

    $user->password = Hash::make($request->password);
    $user->save();

    return response()->json(['message' => 'Register success'], 200);
  }

  /**
   * Update user
   * Store updated user in database
   * For profile
   */
  public function update($id, Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'string|required',
      'profile' => 'image',
      'bio' => 'string|required'
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => 'Invalid input'], 422);
    }

    $user = User::find($id);
    $updateData = [
      'name' => $request->name,
      'username' => $user->username,
      'email' => $user->email,
      'bio' => $request->bio
    ];

    $pict = $request->file('profile');
    if ($pict) {
      $pict_url = $pict->store('uploads');
      $pict->move(public_path('uploads'), $pict_url);
      $updateData['profile'] = $pict_url;
    }

    $user->update($updateData);

    return response()->json(['message' => 'Update user success'], 200);
  }

  /**
   * Me
   * Get authenticated user data
   */
  public function me()
  {
    return auth()->user();
  }

  /**
   * Logout
   * Revoke token from column remember_token in users table
   */
  public function logout()
  {
    $user  = auth()->user();
    User::where(['id' => $user->id])->update(['remember_token' => null]);
    return response()->json(['message' => 'Logout success'], 200);
  }
}
