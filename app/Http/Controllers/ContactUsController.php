<?php

namespace App\Http\Controllers;

use App\Models\MBIContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'phone' => ['nullable', 'string', 'max:50'],
                'message' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $data = $validator->validated();
            MBIContactUs::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'message' => $data['message'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Submitted successfully',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
