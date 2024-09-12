<?php

namespace App\Http\Controllers;

use App\Activity;
use App\CustomerComplaint;
use App\CustomerFeedback;
use App\CustomerRequirement;
use App\RequestProductEvaluation;
use App\SampleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); 
        $userByUser = optional(Auth::user())->user_id; // Safely access user_id
        $role = optional(Auth::user())->role;
        
        if (!$userId && !$userByUser && !$role) {
            // Handle case where there is no authenticated user
            return redirect()->route('login'); // Or handle it in another appropriate way
        }

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
        function countCustomerRequirements($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null ) {
            return CustomerRequirement::where(function($query) use ($userId, $userByUser) {
                    $query->where('PrimarySalesPersonId', $userId)
                        ->orWhere('SecondarySalesPersonId', $userId)
                        ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                })
                ->where($field, $value)
                ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
                    return $query->where($excludeField, '!=', $excludeValue);
                })
                ->count();
        }
        
        // Get counts for different statuses and progress stages
        $crrCancelled = countCustomerRequirements($userId, $userByUser, 'Status', '50');
        $crrSalesApproval = countCustomerRequirements($userId, $userByUser, 'Progress', '10', 'Status', 50);
        $crrSalesApproved = countCustomerRequirements($userId, $userByUser, 'Progress', '20', 'Status', 50);
        $crrSalesAccepted = countCustomerRequirements($userId, $userByUser, 'Progress', '70', 'Status', 50);
        $crrRnDOngoing = countCustomerRequirements($userId, $userByUser, 'Progress', '50', 'Status', 50);
        $crrRnDPending = countCustomerRequirements($userId, $userByUser, 'Progress', '55', 'Status', 50);
        $crrRnDInitial = countCustomerRequirements($userId, $userByUser, 'Progress', '57', 'Status', 50);
        $crrRnDFinal = countCustomerRequirements($userId, $userByUser, 'Progress', '81', 'Status', 50);
        $crrRnDCompleted = countCustomerRequirements($userId, $userByUser, 'Progress', '60', 'Status', 50);

        $totalCRRCount = $crrCancelled + $crrSalesApproval + $crrSalesApproved + $crrSalesAccepted + $crrRnDOngoing + $crrRnDPending + $crrRnDInitial + $crrRnDFinal + $crrRnDCompleted;

        // Product Evaluation
        function countProductEvaluation($userId, $userByUser, $field, $value) {
            return RequestProductEvaluation::where(function($query) use ($userId, $userByUser) {
                    $query->where('PrimarySalesPersonId', $userId)
                        ->orWhere('SecondarySalesPersonId', $userId)
                        ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                })
                ->where($field, $value)
                ->count();
        }

        // Get counts for different statuses and progress stages
        $rpeCancelled = countProductEvaluation($userId, $userByUser, 'Status', '50');
        $rpeSalesApproval = countProductEvaluation($userId, $userByUser, 'Progress', '10');
        $rpeSalesApproved = countProductEvaluation($userId, $userByUser, 'Progress', '20');
        $rpeSalesAccepted = countProductEvaluation($userId, $userByUser, 'Progress', '70');
        $rpeRnDOngoing = countProductEvaluation($userId, $userByUser, 'Progress', '50');
        $rpeRnDPending = countProductEvaluation($userId, $userByUser, 'Progress', '55');
        $rpeRnDInitial = countProductEvaluation($userId, $userByUser, 'Progress', '57');
        $rpeRnDFinal = countProductEvaluation($userId, $userByUser, 'Progress', '81');
        $rpeRnDCompleted = countProductEvaluation($userId, $userByUser, 'Progress', '60');

        $totalRPECount = $rpeCancelled + $rpeSalesApproval + $rpeSalesApproved + $rpeSalesAccepted + $rpeRnDOngoing + $rpeRnDPending + $rpeRnDInitial + $rpeRnDFinal + $rpeRnDCompleted;
        
        // Sample Request  
        function countSampleRequest($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
            return SampleRequest::where(function($query) use ($userId, $userByUser) {
                    $query->where('PrimarySalesPersonId', $userId)
                        ->orWhere('SecondarySalesPersonId', $userId)
                        ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                })
                ->where($field, $value)
                ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
                    return $query->where($excludeField, '==', $excludeValue);
                })
                ->count();
        }

        $srfCancelled = countSampleRequest($userId, $userByUser, 'Status', '50');
        $srfSalesApproval = countSampleRequest($userId, $userByUser, 'Progress', '10', 'Status', '50');
        $srfSalesApproved = countSampleRequest($userId, $userByUser, 'Progress', '20', 'Status', '50');
        $srfSalesAccepted = countSampleRequest($userId, $userByUser, 'Progress', '70', 'Status', '50');
        $srfRnDOngoing = countSampleRequest($userId, $userByUser, 'Progress', '50', 'Status', '50');
        $srfRnDPending = countSampleRequest($userId, $userByUser, 'Progress', '55', 'Status', '50');
        $srfRnDInitial = countSampleRequest($userId, $userByUser, 'Progress', '57', 'Status', '50');
        $srfRnDFinal = countSampleRequest($userId, $userByUser, 'Progress', '81', 'Status', '50');
        $srfRnDCompleted = countSampleRequest($userId, $userByUser, 'Progress', '60', 'Status', '50');

        $totalSRFCount = $srfCancelled + $srfSalesApproval + $srfSalesApproved + $srfSalesAccepted + $srfRnDOngoing + $srfRnDPending + $srfRnDInitial + $srfRnDFinal + $srfRnDCompleted;

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

        // Approval
        function countCrrApproval($userId, $userByUser, $field, $value) {
            return CustomerRequirement::where(function($query) use ($userId, $userByUser) {
                    $query->where('PrimarySalesPersonId', $userId)
                        ->orWhere('SecondarySalesPersonId', $userId)
                        ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                })
                ->where($field, $value)
                ->count();
        }
        $srfCancelled = countSampleRequest($userId, $userByUser, 'Status', '50');

        $totalApproval = $crrSalesApproval + $rpeSalesApproval + $srfSalesApproval;

        return view('dashboard.index', compact(
            'totalActivitiesCount', 'openActivitiesCount', 'closedActivitiesCount', 
            'totalCRRCount', 'crrCancelled', 'crrSalesAccepted', 'crrSalesApproval', 
            'crrSalesApproved', 'crrRnDOngoing', 'crrRnDPending', 'crrRnDInitial', 
            'crrRnDFinal', 'crrRnDCompleted', 'totalCustomerServiceCount', 
            'customerComplaintsCount', 'customerFeedbackCount', 'totalRPECount', 'rpeCancelled', 'rpeSalesApproval',
            'rpeSalesApproved', 'rpeSalesAccepted', 'rpeRnDOngoing', 'rpeRnDPending', 'rpeRnDInitial', 'rpeRnDFinal', 'rpeRnDCompleted', 'totalSRFCount', 'srfCancelled', 'srfSalesApproval',
            'srfSalesApproved', 'srfSalesAccepted', 'srfRnDOngoing', 'srfRnDPending', 'srfRnDInitial', 'srfRnDFinal', 'srfRnDCompleted', 'totalApproval', 'role'
        ));
    }
}
