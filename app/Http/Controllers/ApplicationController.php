<?php

namespace App\Http\Controllers;

use App\Models\JobApplySociety;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        $validation = Validation::where('society_id', $user->id)
            ->where('status', 'accepted')
            ->first();

        if(!$validation) {
            return response()->json([
                'message' => 'Your data validator must be accepted by validator before'
            ],401);
        }

        $validator = Validator::make($request->all(), [
            'vacancy_id' => 'required|exists:job_vacancies,id',
            'positions' => 'required|array|min:1',
            'positions.*' => 'string',
            'notes' => 'required|string'
        ]);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ]);
        }
        $existingApplication = JobApplySociety::where('society_id', $user->id)->first();
        if($existingApplication) {
            return response()->json([
                'message' => 'Application for a job can only be once'
            ],401);
        }
        



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
