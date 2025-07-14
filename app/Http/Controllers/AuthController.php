<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'min:8', 'confirmed'],
            ],
            [
                'name.required' => 'Nama anda harap dimasukan',
                'name.string' => 'Nama anda harus format huruf',
                'name.max' => 'Maksimal karakter nama anda adalah 255',
                'email.required' => 'Harap masukan email anda',
                'email.email' => 'Masukan dengan format email yang seharusnya!',
                'email.unique' => 'Email anda sudah digunakan sebelumnya',
                'password.required' => 'Password wajib dimasukan',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak sesuai',
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
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Akun anda berhasil dibuat',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'email', 'max:255', 'exists:users,email'],
                'password' => ['required'],
            ],
            [
                'email.required' => 'Harap masukan email anda',
                'email.email' => 'Masukan dengan format email yang seharusnya!',
                'email.exists' => 'Email anda tidak terdaftar',
                'password.required' => 'Password wajib dimasukan',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Kredensial yang anda masukkan salah!',
            ], 401);
        }

        // generate token
        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil!',
            'user' => $user,
            'token' => $token,
        ], 200);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil. Token telah dihapus.',
        ], 200);
    }
}
