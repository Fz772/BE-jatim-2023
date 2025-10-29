<?php

namespace App\Http\Controllers;

use App\Models\JobApplyPosition;
use App\Models\JobApplySociety;
use App\Models\JobVacancy;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized user'
            ], 401);
        }

        
        $applications = JobApplySociety::with([
            'jobVacancy.jobCategory', 
            'jobVacancy.availablePositions', 
            'jobApplyPositions.position' 
        ])
        ->where('society_id', $user->id)
        ->get();

        
        // $vacancies = $applications->map(function ($app) {
        //     return [
        //         'id' => $app->jobVacancy->id,
        //         'category' => [
        //             'id' => $app->jobVacancy->jobCategory->id,
        //             'job_category' => $app->jobVacancy->jobCategory->job_category,
        //         ],
        //         'company' => $app->jobVacancy->company,
        //         'address' => $app->jobVacancy->address,
        //         'positions' => $app->jobApplyPositions->map(function ($pos) {
        //             return [
        //                 'position' => $pos->availablePosition->position,
        //                 'apply_status' => $pos->apply_status,
        //                 'notes' => $pos->notes,
        //             ];
        //         }),
        //     ];
        // });

        $vacancies = $applications;

        return response()->json([
            'vacancies' => $vacancies
        ], 200);
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
            'notes' => 'required|string',
            
        ]);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ]);
        }
        $existingApplication = JobApplySociety::where('society_id', $user->id)
    ->where('job_vacancy_id', $request->vacancy_id)
    ->first();

        if($existingApplication) {
            return response()->json([
                'message' => 'Application for a job can only be once'
            ],401);
        }

        $vacancy = JobVacancy::with('availablePositions')->find($request->vacancy_id);
        $validPosition = [];


        foreach ($vacancy->availablePositions as $pos) {
            if (in_array($pos->position, $request->positions)) {
                $applyCount = JobApplyPosition::where('position_id', $pos->id)->count();
                if($applyCount < $pos->apply_capacity) {
                    $validPosition[] = $pos;
                }
            }
        }
        
        if (count($validPosition) === 0) {
            return response()->json([
                'message' => 'No available positions to apply'
            ],400 );
        }

        $applySociety = JobApplySociety::create([
            'society_id' => $user->id,
            'job_vacancy_id' => $request->vacancy_id,
            'notes' => $request->notes,
            'date' => now()
        ]);

        foreach ($validPosition as $pos) {
            JobApplyPosition::create([
                'date' => now(),
                'society_id' => $user->id,
                'job_vacancy_id' => $vacancy->id,
                'position_id' => $pos->id,
                'job_apply_societies_id' => $applySociety->id,
                'status' => 'pending'
                
                
            ]);
        }
        return response()->json([
            'message' => 'Applying for job successfully'
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
