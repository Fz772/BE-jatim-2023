<?php

namespace App\Http\Controllers;


use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function Login(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_card_number' => 'required|string',
            'password' => 'required|string'
        ]);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'error' => $validator->errors()
            ],422);
        }

        $society = Society::where('id_card_number', $request->input('id_card_number'))->first();

        if(!$society || $request->input('password') !== $society->password) {
            return response()->json([
                'message' => 'ID Card Number or Password incorrect'
            ],401);
        }

        $token = $society->createToken('auth_token')->plainTextToken;
        $society->load('regional');
        
        return response()->json([
            "name" => $society->name,
            "born_date" => $society->born_date,
            "gender" => $society->gender,
            "address" => $society->address,
            "token" => $token,
            "regional" => [
                "id" => $society->regional->id,
                "province" => $society->regional->province,
                "district" => $society->regional->district
            ]
        ]);

    }

    public function Logout(Request $request) {
        
    }
}
