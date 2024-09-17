<?php

namespace App\Http\Controllers;

use App\Activity;
use App\CustomerComplaint;
use App\CustomerFeedback;
use App\CustomerRequirement;
use App\PriceMonitoring;
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
        function countCustomerRequirements($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
            return CustomerRequirement::where(function($query) use ($userId, $userByUser) {
                    $query->where('SecondarySalesPersonId', $userId)
                        // ->orWhere('SecondarySalesPersonId', $userId)
                        // ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                })
                ->where($field, $value)
                ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
                    return $query->where($excludeField, '=', $excludeValue); // Exclude records where this condition matches
                })
                ->count();
        }
        
        // Get counts for different statuses and progress stages
        $crrCancelled = countCustomerRequirements($userId, $userByUser, 'Status', '50');
        $crrSalesApproval = countCustomerRequirements($userId, $userByUser, 'Progress', '10', 'Status', 10);
        $crrSalesApproved = countCustomerRequirements($userId, $userByUser, 'Progress', '20');
        $crrSalesAccepted = countCustomerRequirements($userId, $userByUser, 'Progress', '70');
        $crrRnDOngoing = countCustomerRequirements($userId, $userByUser, 'Progress', '50');
        $crrRnDPending = countCustomerRequirements($userId, $userByUser, 'Progress', '55');
        $crrRnDInitial = countCustomerRequirements($userId, $userByUser, 'Progress', '57');
        $crrRnDFinal = countCustomerRequirements($userId, $userByUser, 'Progress', '81');
        $crrRnDCompleted = countCustomerRequirements($userId, $userByUser, 'Progress', '60');
        
        // Calculate total CRR count
        $totalCRRCount = $crrCancelled + $crrSalesApproval + $crrSalesApproved + $crrSalesAccepted + $crrRnDOngoing + $crrRnDPending + $crrRnDInitial + $crrRnDFinal + $crrRnDCompleted;        

        // Product Evaluation
        function countProductEvaluation($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
            return RequestProductEvaluation::where(function($query) use ($userId, $userByUser) {
                    $query->where('SecondarySalesPersonId', $userId)
                        // ->orWhere('SecondarySalesPersonId', $userId)
                        // ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                })
                ->where($field, $value)
                ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
                    return $query->where($excludeField, '=', $excludeValue); // Exclude records where this condition matches
                })
                ->count();
        }

        // Get counts for different statuses and progress stages
        $rpeCancelled = countProductEvaluation($userId, $userByUser, 'Status', '50');
        $rpeSalesApproval = countProductEvaluation($userId, $userByUser, 'Progress', '10', 'Status', 10);
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
                    $query->where('SecondarySalesPersonId', $userId)
                        // ->orWhere('SecondarySalesPersonId', $userId)
                        // ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                })
                ->where($field, $value)
                ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
                    return $query->where($excludeField, '=', $excludeValue);
                })
                ->count();
        }

        $srfCancelled = countSampleRequest($userId, $userByUser, 'Status', '50');
        $srfSalesApproval = countSampleRequest($userId, $userByUser, 'Progress', '10', 'Status', 10);
        $srfSalesApproved = countSampleRequest($userId, $userByUser, 'Progress', '20', );
        $srfSalesAccepted = countSampleRequest($userId, $userByUser, 'Progress', '70', );
        $srfRnDOngoing = countSampleRequest($userId, $userByUser, 'Progress', '50', );
        $srfRnDPending = countSampleRequest($userId, $userByUser, 'Progress', '55', );
        $srfRnDInitial = countSampleRequest($userId, $userByUser, 'Progress', '57', );
        $srfRnDFinal = countSampleRequest($userId, $userByUser, 'Progress', '81', );
        $srfRnDCompleted = countSampleRequest($userId, $userByUser, 'Progress', '60', );

        $totalSRFCount = $srfCancelled + $srfSalesApproval + $srfSalesApproved + $srfSalesAccepted + $srfRnDOngoing + $srfRnDPending + $srfRnDInitial + $srfRnDFinal + $srfRnDCompleted;

        // Price Request
        function countPriceRequest($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
            return PriceMonitoring::where(function($query) use ($userId, $userByUser) {
                    $query->where('SecondarySalesPersonId', $userId)
                        // ->orWhere('SecondarySalesPersonId', $userId)
                        // ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                })
                ->where($field, $value)
                ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
                    return $query->where($excludeField, '=', $excludeValue);
                })
                ->count();
        }

        $prfSalesApproval = countPriceRequest($userId, $userByUser, 'Progress', '10', 'Status', 10);
        $prfWaiting = countPriceRequest($userId, $userByUser, 'Progress', '20', );
        $prfReopened = countPriceRequest($userId, $userByUser, 'Progress', '70', );
        $prfClosed = countPriceRequest($userId, $userByUser, 'Progress', '50', );
        $prfManagerApproval = countPriceRequest($userId, $userByUser, 'Progress', '55', );
        $srfRnDInitial = countPriceRequest($userId, $userByUser, 'Progress', '57', );

        $totalSRFCount = $srfCancelled + $srfSalesApproval + $srfSalesApproved + $srfSalesAccepted + $srfRnDOngoing + $srfRnDPending + $srfRnDInitial + $srfRnDFinal + $srfRnDCompleted;

        $totalApproval = $crrSalesApproval + $rpeSalesApproval + $srfSalesApproval + $prfSalesApproval;

        // RND Approval
        $crrRNDInitialReview = CustomerRequirement::where('Status', '10')->where('Progress', '57')->count();
        $rpeRNDInitialReview = RequestProductEvaluation::where('Status', '10')->where('Progress', '57')->count();
        $srfRNDInitialReview = SampleRequest::where('Status', '10')->where('Progress', '57')->count();

        $totalInitialReview = $crrRNDInitialReview + $rpeRNDInitialReview + $srfRNDInitialReview;

        $crrRNDFinallReview = CustomerRequirement::where('Status', '10')->where('Progress', '81')->count();
        $rpeRNDFinallReview = RequestProductEvaluation::where('Status', '10')->where('Progress', '81')->count();
        $srfRNDFinallReview = SampleRequest::where('Status', '10')->where('Progress', '81')->count();

        $totalFinalReview = $crrRNDFinallReview + $rpeRNDFinallReview + $srfRNDFinallReview;

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
            'customerComplaintsCount', 'customerFeedbackCount', 'totalRPECount', 'rpeCancelled', 'rpeSalesApproval',
            'rpeSalesApproved', 'rpeSalesAccepted', 'rpeRnDOngoing', 'rpeRnDPending', 'rpeRnDInitial', 'rpeRnDFinal', 'rpeRnDCompleted', 'totalSRFCount', 'srfCancelled', 'srfSalesApproval',
            'srfSalesApproved', 'srfSalesAccepted', 'srfRnDOngoing', 'srfRnDPending', 'srfRnDInitial', 'srfRnDFinal', 'srfRnDCompleted', 'totalApproval', 'role', 'prfSalesApproval', 'crrRNDInitialReview', 'rpeRNDInitialReview', 'srfRNDInitialReview', 'totalInitialReview'
        ));
    }
}
