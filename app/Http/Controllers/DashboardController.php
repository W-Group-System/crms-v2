<?php

namespace App\Http\Controllers;

use App\Activity;
use App\CustomerComplaint;
use App\CustomerFeedback;
use App\CustomerRequirement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); 
        $userByUser = Auth::user()->user_id; 

        // Activities
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

        // Customer Requirement
        function countCustomerRequirements($userId, $userByUser, $field, $value) {
            return CustomerRequirement::where(function($query) use ($userId, $userByUser) {
                    $query->where('PrimarySalesPersonId', $userId)
                        ->orWhere('SecondarySalesPersonId', $userId)
                        ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                })
                ->where($field, $value)
                ->count();
        }
        
        // Get counts for different statuses and progress stages
        $crrCancelled = countCustomerRequirements($userId, $userByUser, 'Status', '50');
        $crrSalesApproval = countCustomerRequirements($userId, $userByUser, 'Progress', '10');
        $crrSalesApproved = countCustomerRequirements($userId, $userByUser, 'Progress', '20');
        $crrSalesAccepted = countCustomerRequirements($userId, $userByUser, 'Progress', '70');
        $crrRnDOngoing = countCustomerRequirements($userId, $userByUser, 'Progress', '50');
        $crrRnDPending = countCustomerRequirements($userId, $userByUser, 'Progress', '55');
        $crrRnDInitial = countCustomerRequirements($userId, $userByUser, 'Progress', '57');
        $crrRnDFinal = countCustomerRequirements($userId, $userByUser, 'Progress', '81');
        $crrRnDCompleted = countCustomerRequirements($userId, $userByUser, 'Progress', '60');

        $totalCRRCount = $crrCancelled + $crrSalesApproval + $crrSalesApproved + $crrSalesAccepted + $crrRnDOngoing + $crrRnDPending + $crrRnDInitial + $crrRnDFinal + $crrRnDCompleted;

        // Customer Service
        $customerComplaintsCount = CustomerComplaint::where(function($query) use ($userId, $userByUser) {
            $query->where('ReceivedByUserId', $userId)
                ->orWhere('ReceivedByUserId', $userByUser);
        })->where('Status', '10')->where('Type', '20')->count();
        
        $customerFeedbackCount = CustomerFeedback::where(function($query) use ($userId, $userByUser) {
            $query->where('ReceivedByUserId', $userId)
                ->orWhere('ReceivedByUserId', $userByUser);
        })->where('status', '10')->count();

        $totalCustomerServiceCount = $customerComplaintsCount + $customerFeedbackCount;

        return view('dashboard.index', compact(
            'totalActivitiesCount', 'openActivitiesCount', 'closedActivitiesCount', 
            'totalCRRCount', 'crrCancelled', 'crrSalesAccepted', 'crrSalesApproval', 
            'crrSalesApproved', 'crrRnDOngoing', 'crrRnDPending', 'crrRnDInitial', 
            'crrRnDFinal', 'crrRnDCompleted', 'totalCustomerServiceCount', 
            'customerComplaintsCount', 'customerFeedbackCount'
        ));
    }
}
