<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\ScholarResource;
use App\Http\Resources\ScholarCollection;
use App\Http\Resources;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ScholarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scholars = Scholar::with('user','scholarStatus', 'scholarship_categs')->get();
        return response()->json(new ScholarCollection($scholars), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $scholar = Scholar::create($request->only([
            'user_id','scholarship_categ_id','scholar_photo_filepath','gender','religion','birthdate','birthplace','civil_status','num_fam_mem','school_yr_started',
            'school_yr_graduated','school_id','program','home_visit_sched','home_address_id',
            'fb_account',
        ]));
        return new ScholarResource($scholar);
    }

    /**
     * Display the specified resource.
     */
    public function show(Scholar $scholar)
    {
        $scholarResource = new ScholarResource($scholar);

        // Include the ScholarStatus data
        $scholarResource->additional([
            'scholar_status' => [
                'id' => $scholar->scholar_status_id,
                'name' => $scholar->scholar_status_name,
            ],
            'user' => [
                'id' => $scholar->user_id,
                'first_name' => $scholar->user_first_name,
                'last_name' => $scholar->user_last_name,
                'middle_name' => $scholar->user_middle_name,
                'email_address' => $scholar->user_email_address,
                'user_mobile_num' => $scholar->user_mobile_num,
            ],
            'scholarship_categs' => [
                'id' => $scholar->scholarship_categ_id,
                'scholarship_categ_name' => $scholar->scholarship_categ_name,
            ],
            
        ]);

        return $scholarResource;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scholar $scholar)
    {
        $scholar->update($request->only([
            'scholar_photo_filepath','gender',
            'religion','birthdate','birthplace','civil_status','num_fam_mem','school_yr_started',
            'school_yr_graduated','school_id','program','home_visit_sched','home_address_id',
            'fb_account',
        ]));
        return new ScholarResource($scholar);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scholar $scholar)
    {
        $scholar->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function scholarProfile()
    {
        try {
            // Retrieve the authenticated user's ID
            $userId = Auth::id();

            // Find the scholar profile that belongs to the authenticated user
            $scholar = Scholar::where('user_id', $userId)->first();

            if ($scholar) {
                // Scholar profile found, return as a resource
                return new ScholarResource($scholar);
            } else {
                // Scholar profile not found for the authenticated user
                return response()->json(['message' => 'Scholar profile not found for the authenticated user'], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            // Log the exception for further investigation
            \Log::error('Error in scholarProfile: ' . $e->getMessage());

            return response()->json(['message' => 'Error processing scholar profile'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function updateScholarProfile(Request $request, $id)
    {
        try {
            // Retrieve the authenticated user's ID
            $userId = Auth::id();
    
            // Find the scholar by ID and make sure it belongs to the authenticated user
            $scholar = Scholar::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();
    
            // Update the scholar with the request data
            $scholar->update($request->only([
                'gender', 'religion', 'birthdate', 'birthplace', 'civil_status', 'num_fam_mem',
                'school_yr_started', 'school_yr_graduated', 'school_id', 'program',
                'home_visit_sched', 'fb_account',
            ]));
    
            // Return the updated scholar as a resource
            return new ScholarResource($scholar);
        } catch (\Exception $e) {
            // Log the exception for further investigation
            \Log::error('Error in updateScholarProfile: ' . $e->getMessage());
    
            return response()->json(['message' => 'Scholar not found or does not belong to the authenticated user'], Response::HTTP_NOT_FOUND);
        }
    }

    public function storeScholarProfile(Request $request)
    {
        $scholar = Scholar::create($request->only([
            'user_id', 'scholarship_categ_id', 'scholar_photo_filepath', 'project_partner_id', 'gender', 'religion', 'birthdate', 'birthplace', 'civil_status', 'num_fam_mem', 'school_yr_started', 'school_yr_graduated', 'school_id', 'program', 'home_visit_sched', 'home_address_id', 'fb_account', 'scholar_status_id'
        ]));
        return new ScholarResource($scholar);
    }
    // public function search(Request $request, $user_id)
    // {
    //     $scholars = Scholar::where('user_id', '=', $user_id)->get();
    //     return ScholarResource::collection($scholars);
    // }
}
