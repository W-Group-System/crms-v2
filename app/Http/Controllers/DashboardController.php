<?php

namespace App\Http\Controllers;

use App\Activity;
use App\CustomerComplaint;
use App\CustomerFeedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); 
        $userByUser = Auth::user()->user_id; 

        // Get the count of open activities
        $openActivitiesCount = Activity::where(function($query) use ($userId, $userByUser) {
            $query->where('PrimaryResponsibleUserId', $userId)
                ->orWhere('SecondaryResponsibleUserId', $userId)
                ->orWhere('PrimaryResponsibleUserId', $userByUser)
                ->orWhere('SecondaryResponsibleUserId', $userByUser);
        })->where('status', '10')->count();

        // Get the count of closed activities
        $closedActivitiesCount = Activity::where(function($query) use ($userId, $userByUser) {
            $query->where('PrimaryResponsibleUserId', $userId)
                ->orWhere('SecondaryResponsibleUserId', $userId)
                ->orWhere('PrimaryResponsibleUserId', $userByUser)
                ->orWhere('SecondaryResponsibleUserId', $userByUser);
        })->where('status', '20')->count();

        // Total activities
        $totalActivitiesCount = $openActivitiesCount + $closedActivitiesCount;

        $customerComplaintsCount = CustomerComplaint::where(function($query) use ($userId, $userByUser) {
            $query->where('ReceivedByUserId', $userId)
                ->orWhere('ReceivedByUserId', $userByUser);
        })->where('status', '10')->count();

        return view('dashboard.index', compact('totalActivitiesCount', 'openActivitiesCount', 'closedActivitiesCount'));
    }
}
