<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use App\Models\Validation;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
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

        $validation = Validation::where('society_id', $user->id)
            ->where('status','accepted')
            ->first();

        if(!$validation) {
            return response()->json([
                'message' => 'Your validation data is not accepted'
            ],403);
        }
        $vacancies = JobVacancy::with(['jobCategory', 'availablePositions.jobApplyPositions'])
    ->where('job_category_id', $validation->job_category_id)
    ->get()
    ->map(function ($vacancy) {
        return [
            'id' => $vacancy->id,
            'category' => [
                'id' => $vacancy->jobCategory->id,
                'job_category' => $vacancy->jobCategory->job_category,
            ],
            'company' => $vacancy->company, 
            'address' => $vacancy->address, 
            'description' => $vacancy->description,
            'available_positions' => $vacancy->availablePositions->map(function ($pos) {
                return [
                    'position' => $pos->position,
                    'capacity' => $pos->capacity,
                    'apply_capacity' => $pos->jobApplyPositions->count(),
                ];
            }),
        ];
    });


            return response()->json([
                $vacancies
            ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
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
                'message' => 'Your validation data is not accepted'
            ],403);
        }
        $vacancy = JobVacancy::with(['jobCategory', 'availablePositions.jobApplyPositions'])
            ->where('id', $id)
            ->first();

        if(!$vacancy) {
            return response()->json([
                'message' => 'Job vacancy not found'
            ],404);
        }

        $data = [
            'vacancy' => [
                'id' => $vacancy->id,
                'category' => [
                    'id' => $vacancy->jobCategory->id,
                    'job_category' => $vacancy->jobCategory->job_category,

                ],
                'company' => $vacancy->company,
                'address' => $vacancy->address,
                'description' => $vacancy->description,
                'available_position' => $vacancy->availablePositions->map(function ($pos) {
                    return [
                        'position' => $pos->position,
                        'capacity' => $pos->capacity,
                        'apply_capacity' => $pos->jobApplyPositions->count(),
                        'apply_count' => $pos->jobApplyPositions->where('status', 'accepted')->count()
                    ];
                }),

            ],
            ];
        return response()->json($data);
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
