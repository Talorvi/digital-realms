<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceTokenController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $deviceTokens = $user->deviceTokens;

        return response()->json($deviceTokens);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|unique:device_tokens,token',
            'device_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = $request->user();
        $user->deviceTokens()->create($validator->validated());

        return response()->json(['message' => 'Device token added successfully']);
    }

    public function destroy(Request $request, DeviceToken $deviceToken)
    {
        if ($request->user()->id !== $deviceToken->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $deviceToken->delete();

        return response()->json(['message' => 'Device token removed successfully']);
    }
}
