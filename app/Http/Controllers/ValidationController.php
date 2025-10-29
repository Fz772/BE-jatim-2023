<?php

namespace App\Http\Controllers;

use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if(!$user) {
            return response()->json([
                'message' => 'Unauthorized user'
            ],401);
        }
        $validation = Validation::where('society_id', $user->id)->first();
        return response()->json([
            'validation' => [
                'id' => $validation->id,
                'status' => $validation->status,
                'work_experience' => $validation->work_experience,
                'job_category_id' => $validation->job_category_id,
                'job_position' => $validation->job_position,
                'reason_accepted' => $validation->reason_accepted,
                'validator_notes' => $validation->validator_notes,
                'validator' => $validation->validator

            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if(!$user) {
            return response()->json([
                'message' => 'Unauthorized user'
            ],401);
        }


        $validator = Validator::make($request->all(),[
            'work_experience' => 'required|string',
            'job_category_id' => 'required|integer',
            'job_position' => 'required|string',
            'reason_accepted' => 'required|string'
        ]);
        if($validator->fails()) {
            return response()->json([
                    'message' => 'validation failed',
                    'error' => $validator->errors()
            ],422);
        }
        // if()

        $validated = $validator->validated();
        if(Validation::where('society_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'You have already submitted a validation request'
            ]);
        }

        Validation::create([
            'society_id' => $user->id,
            'work_experience' => $validated['work_experience'],
            'job_category_id' => $validated['job_category_id'],
            'job_position' => $validated['job_position'],
            'reason_accepted' => $validated['reason_accepted']
        ]);

        

        return response()->json([
            'message' => 'Request data validation sent successful'
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
