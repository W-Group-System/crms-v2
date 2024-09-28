<?php

namespace App\Http\Controllers;

use App\Activity;
use App\CrrPersonnel;
use App\CustomerComplaint;
use App\CustomerFeedback;
use App\CustomerRequirement;
use App\PriceMonitoring;
use App\Product;
use App\RequestProductEvaluation;
use App\SampleRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $role = optional(Auth::user())->role;
        
        // Check user role and redirect accordingly
        if ($role && $role->type == 'RND') {
            return redirect('/dashboard-rnd');
        } elseif ($role && $role->type == 'IS' || $role->type == 'LS') {
            return redirect('/dashboard-sales');
        }
    }

    public function salesIndex()
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

        // Generic function to count records across models
        function countRecords($model, $userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
            return $model::where(function($query) use ($userId, $userByUser) {
                    $query->where('SecondarySalesPersonId', $userId)
                        ->orWhere('SecondarySalesPersonId', $userId)
                        ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                })
                ->where($field, $value)
                ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
                    return $query->where($excludeField, '=', $excludeValue);
                })
                ->count();
        }

        // Models
        $customerRequirementModel = CustomerRequirement::class;
        $productEvaluationModel = RequestProductEvaluation::class;
        $sampleRequestModel = SampleRequest::class;
        $priceRequestModel = PriceMonitoring::class;

        // Customer Requirement counts
        $crrCancelled = countRecords($customerRequirementModel, $userId, $userByUser, 'Status', '50');
        $crrSalesApproval = countRecords($customerRequirementModel, $userId, $userByUser, 'Progress', '10', 'Status', 10);
        $crrSalesApproved = countRecords($customerRequirementModel, $userId, $userByUser, 'Progress', '20');
        $crrSalesAccepted = countRecords($customerRequirementModel, $userId, $userByUser, 'Progress', '70');
        $crrRnDOngoing = countRecords($customerRequirementModel, $userId, $userByUser, 'Progress', '50');
        $crrRnDPending = countRecords($customerRequirementModel, $userId, $userByUser, 'Progress', '55');
        $crrRnDInitial = countRecords($customerRequirementModel, $userId, $userByUser, 'Progress', '57');
        $crrRnDFinal = countRecords($customerRequirementModel, $userId, $userByUser, 'Progress', '81');
        $crrRnDCompleted = countRecords($customerRequirementModel, $userId, $userByUser, 'Progress', '60');
        $totalCRRCount = $crrCancelled + $crrSalesApproval + $crrSalesApproved + $crrSalesAccepted + $crrRnDOngoing + $crrRnDPending + $crrRnDInitial + $crrRnDFinal + $crrRnDCompleted;

        // Product Evaluation counts
        $rpeCancelled = countRecords($productEvaluationModel, $userId, $userByUser, 'Status', '50');
        $rpeSalesApproval = countRecords($productEvaluationModel, $userId, $userByUser, 'Progress', '10', 'Status', 10);
        $rpeSalesApproved = countRecords($productEvaluationModel, $userId, $userByUser, 'Progress', '20');
        $rpeSalesAccepted = countRecords($productEvaluationModel, $userId, $userByUser, 'Progress', '70');
        $rpeRnDOngoing = countRecords($productEvaluationModel, $userId, $userByUser, 'Progress', '50');
        $rpeRnDPending = countRecords($productEvaluationModel, $userId, $userByUser, 'Progress', '55');
        $rpeRnDInitial = countRecords($productEvaluationModel, $userId, $userByUser, 'Progress', '57');
        $rpeRnDFinal = countRecords($productEvaluationModel, $userId, $userByUser, 'Progress', '81');
        $rpeRnDCompleted = countRecords($productEvaluationModel, $userId, $userByUser, 'Progress', '60');
        $totalRPECount = $rpeCancelled + $rpeSalesApproval + $rpeSalesApproved + $rpeSalesAccepted + $rpeRnDOngoing + $rpeRnDPending + $rpeRnDInitial + $rpeRnDFinal + $rpeRnDCompleted;

        // Sample Request counts
        $srfCancelled = countRecords($sampleRequestModel, $userId, $userByUser, 'Status', '50');
        $srfSalesApproval = countRecords($sampleRequestModel, $userId, $userByUser, 'Progress', '10', 'Status', 10);
        $srfSalesApproved = countRecords($sampleRequestModel, $userId, $userByUser, 'Progress', '20');
        $srfSalesAccepted = countRecords($sampleRequestModel, $userId, $userByUser, 'Progress', '70');
        $srfRnDOngoing = countRecords($sampleRequestModel, $userId, $userByUser, 'Progress', '50');
        $srfRnDPending = countRecords($sampleRequestModel, $userId, $userByUser, 'Progress', '55');
        $srfRnDInitial = countRecords($sampleRequestModel, $userId, $userByUser, 'Progress', '57');
        $srfRnDFinal = countRecords($sampleRequestModel, $userId, $userByUser, 'Progress', '81');
        $srfRnDCompleted = countRecords($sampleRequestModel, $userId, $userByUser, 'Progress', '60');
        $totalSRFCount = $srfCancelled + $srfSalesApproval + $srfSalesApproved + $srfSalesAccepted + $srfRnDOngoing + $srfRnDPending + $srfRnDInitial + $srfRnDFinal + $srfRnDCompleted;

        // Price Request counts
        $prfSalesApproval = countRecords($priceRequestModel, $userId, $userByUser, 'Progress', '10', 'Status', 10);
        $prfWaiting = countRecords($priceRequestModel, $userId, $userByUser, 'Progress', '20');
        $prfReopened = countRecords($priceRequestModel, $userId, $userByUser, 'Progress', '25');
        $prfClosed = countRecords($priceRequestModel, $userId, $userByUser, 'Progress', '30');
        $prfManagerApproval = countRecords($priceRequestModel, $userId, $userByUser, 'Progress', '40');
        $totalPRFCount = $prfSalesApproval + $prfWaiting + $prfReopened + $prfClosed + $prfManagerApproval;


        // Customer Requirement
        // function countCustomerRequirements($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
        //     return CustomerRequirement::where(function($query) use ($userId, $userByUser) {
        //             $query->where('SecondarySalesPersonId', $userId)
        //                 ->orWhere('SecondarySalesPersonId', $userId)
        //                 ->orWhere('PrimarySalesPersonId', $userByUser)
        //                 ->orWhere('SecondarySalesPersonId', $userByUser);
        //         })
        //         ->where($field, $value)
        //         ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
        //             return $query->where($excludeField, '=', $excludeValue); // Exclude records where this condition matches
        //         })
        //         ->count();
        // }
        
        // // Get counts for different statuses and progress stages
        // $crrCancelled = countCustomerRequirements($userId, $userByUser, 'Status', '50');
        // $crrSalesApproval = countCustomerRequirements($userId, $userByUser, 'Progress', '10', 'Status', 10);
        // $crrSalesApproved = countCustomerRequirements($userId, $userByUser, 'Progress', '20');
        // $crrSalesAccepted = countCustomerRequirements($userId, $userByUser, 'Progress', '70');
        // $crrRnDOngoing = countCustomerRequirements($userId, $userByUser, 'Progress', '50');
        // $crrRnDPending = countCustomerRequirements($userId, $userByUser, 'Progress', '55');
        // $crrRnDInitial = countCustomerRequirements($userId, $userByUser, 'Progress', '57');
        // $crrRnDFinal = countCustomerRequirements($userId, $userByUser, 'Progress', '81');
        // $crrRnDCompleted = countCustomerRequirements($userId, $userByUser, 'Progress', '60');
        
        // // Calculate total CRR count
        // $totalCRRCount = $crrCancelled + $crrSalesApproval + $crrSalesApproved + $crrSalesAccepted + $crrRnDOngoing + $crrRnDPending + $crrRnDInitial + $crrRnDFinal + $crrRnDCompleted;        

        // // Product Evaluation
        // function countProductEvaluation($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
        //     return RequestProductEvaluation::where(function($query) use ($userId, $userByUser) {
        //             $query->where('SecondarySalesPersonId', $userId)
        //                 // ->orWhere('SecondarySalesPersonId', $userId)
        //                 // ->orWhere('PrimarySalesPersonId', $userByUser)
        //                 ->orWhere('SecondarySalesPersonId', $userByUser);
        //         })
        //         ->where($field, $value)
        //         ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
        //             return $query->where($excludeField, '=', $excludeValue); // Exclude records where this condition matches
        //         })
        //         ->count();
        // }

        // // Get counts for different statuses and progress stages
        // $rpeCancelled = countProductEvaluation($userId, $userByUser, 'Status', '50');
        // $rpeSalesApproval = countProductEvaluation($userId, $userByUser, 'Progress', '10', 'Status', 10);
        // $rpeSalesApproved = countProductEvaluation($userId, $userByUser, 'Progress', '20');
        // $rpeSalesAccepted = countProductEvaluation($userId, $userByUser, 'Progress', '70');
        // $rpeRnDOngoing = countProductEvaluation($userId, $userByUser, 'Progress', '50');
        // $rpeRnDPending = countProductEvaluation($userId, $userByUser, 'Progress', '55');
        // $rpeRnDInitial = countProductEvaluation($userId, $userByUser, 'Progress', '57');
        // $rpeRnDFinal = countProductEvaluation($userId, $userByUser, 'Progress', '81');
        // $rpeRnDCompleted = countProductEvaluation($userId, $userByUser, 'Progress', '60');

        // $totalRPECount = $rpeCancelled + $rpeSalesApproval + $rpeSalesApproved + $rpeSalesAccepted + $rpeRnDOngoing + $rpeRnDPending + $rpeRnDInitial + $rpeRnDFinal + $rpeRnDCompleted;
        
        // // Sample Request  
        // function countSampleRequest($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
        //     return SampleRequest::where(function($query) use ($userId, $userByUser) {
        //             $query->where('SecondarySalesPersonId', $userId)
        //                 // ->orWhere('SecondarySalesPersonId', $userId)
        //                 // ->orWhere('PrimarySalesPersonId', $userByUser)
        //                 ->orWhere('SecondarySalesPersonId', $userByUser);
        //         })
        //         ->where($field, $value)
        //         ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
        //             return $query->where($excludeField, '=', $excludeValue);
        //         })
        //         ->count();
        // }

        // $srfCancelled = countSampleRequest($userId, $userByUser, 'Status', '50');
        // $srfSalesApproval = countSampleRequest($userId, $userByUser, 'Progress', '10', 'Status', 10);
        // $srfSalesApproved = countSampleRequest($userId, $userByUser, 'Progress', '20', );
        // $srfSalesAccepted = countSampleRequest($userId, $userByUser, 'Progress', '70', );
        // $srfRnDOngoing = countSampleRequest($userId, $userByUser, 'Progress', '50', );
        // $srfRnDPending = countSampleRequest($userId, $userByUser, 'Progress', '55', );
        // $srfRnDInitial = countSampleRequest($userId, $userByUser, 'Progress', '57', );
        // $srfRnDFinal = countSampleRequest($userId, $userByUser, 'Progress', '81', );
        // $srfRnDCompleted = countSampleRequest($userId, $userByUser, 'Progress', '60', );

        // $totalSRFCount = $srfCancelled + $srfSalesApproval + $srfSalesApproved + $srfSalesAccepted + $srfRnDOngoing + $srfRnDPending + $srfRnDInitial + $srfRnDFinal + $srfRnDCompleted;

        // // Price Request
        // function countPriceRequest($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
        //     return PriceMonitoring::where(function($query) use ($userId, $userByUser) {
        //             $query->where('SecondarySalesPersonId', $userId)
        //                 ->orWhere('SecondarySalesPersonId', $userId)
        //                 ->orWhere('PrimarySalesPersonId', $userByUser)
        //                 ->orWhere('SecondarySalesPersonId', $userByUser);
        //         })
        //         ->where($field, $value)
        //         ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
        //             return $query->where($excludeField, '=', $excludeValue);
        //         })
        //         ->count();
        // }

        // $prfSalesApproval = countPriceRequest($userId, $userByUser, 'Progress', '10', 'Status', 10);
        // $prfWaiting = countPriceRequest($userId, $userByUser, 'Progress', '20', );
        // $prfReopened = countPriceRequest($userId, $userByUser, 'Progress', '25', );
        // $prfClosed = countPriceRequest($userId, $userByUser, 'Progress', '30', );
        // $prfManagerApproval = countPriceRequest($userId, $userByUser, 'Progress', '40', );

        // $totalPRFCount = $prfSalesApproval + $prfWaiting + $prfReopened + $prfClosed + $prfManagerApproval;

        // Sales Approval
        function countApproval($model, $userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
            return $model::where(function($query) use ($userId, $userByUser) {
                        $query->where('SecondarySalesPersonId', $userId)
                            ->orWhere('SecondarySalesPersonId', $userByUser);
                    })
                    ->where($field, $value)
                    ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
                        return $query->where($excludeField, '=', $excludeValue);
                    })
                    ->count();
        }

        // Counting approvals for different models
        $crrSalesForApproval = countApproval(CustomerRequirement::class, $userId, $userByUser, 'Progress', '10', 'Status', 10);
        $rpeSalesForApproval = countApproval(RequestProductEvaluation::class, $userId, $userByUser, 'Progress', '10', 'Status', 10);
        $srfSalesForApproval = countApproval(SampleRequest::class, $userId, $userByUser, 'Progress', '10', 'Status', 10);
        $prfSalesForApproval = countApproval(PriceMonitoring::class, $userId, $userByUser, 'Progress', '10', 'Status', 10);

        // Total approval count
        $totalApproval = $crrSalesForApproval + $rpeSalesForApproval + $srfSalesForApproval + $prfSalesForApproval;

        /************* RND *************/

        // RND Approval
        $crrRNDInitialReview = CustomerRequirement::where('Status', '10')->where('Progress', '57')->count();
        $rpeRNDInitialReview = RequestProductEvaluation::where('Status', '10')->where('Progress', '57')->count();
        $srfRNDInitialReview = SampleRequest::where('Status', '10')->where('Progress', '57')->count();

        $totalInitialReview = $crrRNDInitialReview + $rpeRNDInitialReview + $srfRNDInitialReview;

        $crrRNDFinallReview = CustomerRequirement::where('Status', '10')->where('Progress', '81')->count();
        $rpeRNDFinallReview = RequestProductEvaluation::where('Status', '10')->where('Progress', '81')->count();
        $srfRNDFinallReview = SampleRequest::where('Status', '10')->where('Progress', '81')->count();

        $totalFinalReview = $crrRNDFinallReview + $rpeRNDFinallReview + $srfRNDFinallReview;

        // RND New Request
        $crrRNDNew = CustomerRequirement::where('Status', '10')->where('Progress', '30')->count();
        $rpeRNDNew = RequestProductEvaluation::where('Status', '10')->where('Progress', '30')->count();
        $srfRNDNew = SampleRequest::where('Status', '10')->where('Progress', '30')->count();

        $totalNewRequest = $crrRNDNew + $rpeRNDNew + $srfRNDNew;

        // RND Due 
        $crrDueToday = CustomerRequirement::where('Status', '10')->where('DueDate', '<', now())->count();
        $rpeDueToday = RequestProductEvaluation::where('Status', '10')->where('DueDate', '<', now())->count();
        $srfDueToday = SampleRequest::where('Status', '10')->where('DateRequired', '<', now())->count();

        $totalDueToday = $crrDueToday + $rpeDueToday + $srfDueToday;

        $crrDue = CustomerRequirement::where('Status', '10')
            ->where('DueDate', '<', now())
            ->whereIn('id', function($query) use ($userId, $userByUser) {
                $query->select('CustomerRequirementId')
                    ->from('crrpersonnels')
                    ->where('PersonnelUserId', $userId)
                    ->orWhere('PersonnelUserId', $userByUser);
            })
            ->count(); // Count the records that match the criteria

        $rpeDue = RequestProductEvaluation::where('Status', '10')
            ->where('DueDate', '<', now())
            ->whereIn('id', function($query) use ($userId, $userByUser) {
                $query->select('RequestProductEvaluationId')
                    ->from('rpepersonnels')
                    ->where('PersonnelUserId', $userId)
                    ->orWhere('PersonnelUserId', $userByUser);
            })
            ->count(); // Count the records that match the criteria
        
        $srfDue = SampleRequest::where('Status', '10')
            ->where('DateRequired', '<', now())
            ->whereIn('id', function($query) use ($userId, $userByUser) {
                $query->select('SampleRequestId')
                    ->from('srfpersonnels')
                    ->where('PersonnelUserId', $userId)
                    ->orWhere('PersonnelUserId', $userByUser);
            })
            ->count(); // Count the records that match the criteria
        
        $totalDue = $crrDue + $rpeDue + $srfDue;
        
        // Open Transaction
        $crrRndOpen = CustomerRequirement::where('Status', '10')->count();
        $rpeRndOpen = RequestProductEvaluation::where('Status', '10')->count();
        $srfRndOpen = SampleRequest::where('Status', '10')->count();

        // Closed Transaction
        $crrRndClosed = CustomerRequirement::where('Status', '30')->count();
        $rpeRndClosed = RequestProductEvaluation::where('Status', '30')->count();
        $srfRndClosed = SampleRequest::where('Status', '30')->count();
        
        // Open 
        $rndCrrOpen = CustomerRequirement::where('Status', '10')
            ->whereIn('id', function($query) use ($userId, $userByUser) {
                $query->select('CustomerRequirementId')
                    ->from('crrpersonnels')
                    ->where('PersonnelUserId', '=',  $userId)
                    ->orWhere('PersonnelUserId', '=', $userByUser);
            })
            ->count(); 

        $rndRpeOpen = RequestProductEvaluation::where('Status', '10')
            ->whereIn('id', function($query) use ($userId, $userByUser) {
                $query->select('RequestProductEvaluationId')
                    ->from('rpepersonnels')
                    ->where('PersonnelUserId', $userId)
                    ->orWhere('PersonnelUserId', $userByUser);
            })
            ->count(); 
            
        $rndSrfOpen = SampleRequest::where('Status', '10')
            ->whereIn('Id', function($query) use ($userId, $userByUser) {
                $query->select('SampleRequestId')
                    ->from('srfpersonnels')
                    ->where('PersonnelUserId', $userId)
                    ->orWhere('PersonnelUserId', $userByUser);
            })
            ->count(); 
              
        $totalOpenRND = $rndCrrOpen + $rndRpeOpen + $rndSrfOpen;

        // Closed
        $rndCrrClosed = CustomerRequirement::where('Status', '30')
            ->whereIn('id', function($query) use ($userId, $userByUser) {
                $query->select('CustomerRequirementId')
                    ->from('crrpersonnels')
                    ->where('PersonnelUserId', $userId)
                    ->orWhere('PersonnelUserId', $userByUser);
            })
            ->count(); 

        $rndRpeClosed = RequestProductEvaluation::where('Status', '30')
            ->whereIn('id', function($query) use ($userId, $userByUser) {
                $query->select('RequestProductEvaluationId')
                    ->from('rpepersonnels')
                    ->where('PersonnelUserId', $userId)
                    ->orWhere('PersonnelUserId', $userByUser);
            })
            ->count(); 

        $rndSrfClosed = SampleRequest::where('Status', '30')
            ->whereIn('Id', function($query) use ($userId, $userByUser) {
                $query->select('SampleRequestId')
                    ->from('srfpersonnels')
                    ->where('PersonnelUserId', $userId)
                    ->orWhere('PersonnelUserId', $userByUser);
            })
            ->count();

        $totalClosedRND = $rndCrrClosed + $rndRpeClosed + $rndSrfClosed;
        // New Products
        $newProducts = Product::where(function($query) {
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            })
            ->orWhere(function($query) {
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                    ->whereYear('created_at', Carbon::now()->subMonth()->year);
            })
            ->orderBy('created_at', 'desc') 
            ->get();

        // CRR counts RND
        function countCustomerRequirementsRND($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
            return CustomerRequirement::whereIn('id', function($query) use ($userId, $userByUser) {
                        $query->select('CustomerRequirementId')
                            ->from('crrpersonnels')
                            ->where('PersonnelUserId', $userId)
                            ->orWhere('PersonnelUserId', $userByUser);
                    })
                    ->where($field, $value)
                    ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
                        return $query->where($excludeField, '!=', $excludeValue); // Exclude records where this condition matches
                    })
                    ->count();
        }

        $crrCancelledRND = countCustomerRequirementsRND($userId, $userByUser, 'Status', '50', );
        $crrSalesApprovalRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '10', 'Status', 10);
        $crrSalesApprovedRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '20');
        $crrSalesAcceptedRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '70');
        $crrRnDReceivedRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '35');
        $crrRnDOngoingRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '50');
        $crrRnDPendingRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '55');
        $crrRnDInitialRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '57');
        $crrRnDFinalRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '81');
        $crrRnDCompletedRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '60');
        $totalCRRCountRND = $crrCancelledRND + $crrSalesApprovalRND + $crrSalesApprovedRND + $crrSalesAcceptedRND + $crrRnDReceivedRND + $crrRnDOngoingRND + $crrRnDPendingRND + $crrRnDInitialRND + $crrRnDFinalRND + $crrRnDCompletedRND;

        // RPE counts RND
        function countProductEvaluationRND($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
            return RequestProductEvaluation::whereIn('id', function($query) use ($userId, $userByUser) {
                        $query->select('RequestProductEvaluationId')
                            ->from('rpepersonnels')
                            ->where('PersonnelUserId', $userId)
                            ->orWhere('PersonnelUserId', $userByUser);
                    })
                    ->where($field, $value)
                    ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
                        return $query->where($excludeField, '!=', $excludeValue); // Exclude records where this condition matches
                    })
                    ->count();
        }

        $rpeCancelledRND = countProductEvaluationRND($userId, $userByUser, 'Status', '50', );
        $rpeSalesApprovalRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '10', 'Status', 10);
        $rpeSalesApprovedRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '20');
        $rpeSalesAcceptedRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '70');
        $rpeRnDReceivedRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '35');
        $rpeRnDOngoingRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '50');
        $rpeRnDPendingRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '55');
        $rpeRnDInitialRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '57');
        $rpeRnDFinalRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '81');
        $rpeRnDCompletedRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '60');
        $totalRPECountRND = $rpeCancelledRND + $rpeSalesApprovalRND + $rpeSalesApprovedRND + $rpeSalesAcceptedRND + $rpeRnDReceivedRND + $rpeRnDOngoingRND + $rpeRnDPendingRND + $rpeRnDInitialRND + $rpeRnDFinalRND + $rpeRnDCompletedRND;

        // SRF counts RND
        function countSampleRequestRND($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
            return SampleRequest::whereIn('id', function($query) use ($userId, $userByUser) {
                        $query->select('SampleRequestId')
                            ->from('srfpersonnels')
                            ->where('PersonnelUserId', $userId)
                            ->orWhere('PersonnelUserId', $userByUser);
                    })
                    ->where($field, $value)
                    ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
                        return $query->where($excludeField, '!=', $excludeValue); // Exclude records where this condition matches
                    })
                    ->count();
        }

        $srfCancelledRND = countSampleRequestRND($userId, $userByUser, 'Status', '50', );
        $srfSalesApprovalRND = countSampleRequestRND($userId, $userByUser, 'Progress', '10', 'Status', 10);
        $srfSalesApprovedRND = countSampleRequestRND($userId, $userByUser, 'Progress', '20');
        $srfSalesAcceptedRND = countSampleRequestRND($userId, $userByUser, 'Progress', '70');
        $srfRnDReceivedRND = countSampleRequestRND($userId, $userByUser, 'Progress', '35');
        $srfRnDOngoingRND = countSampleRequestRND($userId, $userByUser, 'Progress', '50');
        $srfRnDPendingRND = countSampleRequestRND($userId, $userByUser, 'Progress', '55');
        $srfRnDInitialRND = countSampleRequestRND($userId, $userByUser, 'Progress', '57');
        $srfRnDFinalRND = countSampleRequestRND($userId, $userByUser, 'Progress', '81');
        $srfRnDCompletedRND = countSampleRequestRND($userId, $userByUser, 'Progress', '60');
        $totalSRFCountRND = $srfCancelledRND + $srfSalesApprovalRND + $srfSalesApprovedRND + $srfSalesAcceptedRND + $srfRnDReceivedRND + $srfRnDOngoingRND + $srfRnDPendingRND + $srfRnDInitialRND + $srfRnDFinalRND + $srfRnDCompletedRND;

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
            'srfSalesApproved', 'srfSalesAccepted', 'srfRnDOngoing', 'srfRnDPending', 'srfRnDInitial', 'srfRnDFinal', 'srfRnDCompleted', 'totalApproval', 'role', 'prfSalesApproval', 'crrRNDInitialReview', 'rpeRNDInitialReview', 'srfRNDInitialReview', 'totalInitialReview', 'crrRNDNew', 'rpeRNDNew', 'srfRNDNew', 'totalNewRequest', 'totalPRFCount', 'prfSalesApproval', 'prfWaiting', 'prfReopened', 'prfClosed', 'prfManagerApproval', 'crrSalesForApproval', 'rpeSalesForApproval', 'srfSalesForApproval', 'prfSalesForApproval', 'crrDue', 'rpeDue', 'srfDue', 'totalDue', 'newProducts', 'crrCancelledRND', 'crrSalesApprovalRND', 'crrSalesApprovedRND', 'crrSalesAcceptedRND', 'crrRnDOngoingRND', 'crrRnDPendingRND', 'crrRnDInitialRND', 'crrRnDFinalRND', 'crrRnDCompletedRND', 'crrRnDReceivedRND', 'totalCRRCountRND', 'rpeCancelledRND', 'rpeSalesApprovalRND', 'rpeSalesApprovedRND', 'rpeSalesAcceptedRND', 'rpeRnDOngoingRND', 'rpeRnDPendingRND', 'rpeRnDInitialRND', 'rpeRnDFinalRND', 'rpeRnDCompletedRND', 'rpeRnDReceivedRND', 'totalRPECountRND', 'srfCancelledRND', 'srfSalesApprovalRND', 'srfSalesApprovedRND', 'srfSalesAcceptedRND', 'srfRnDOngoingRND', 'srfRnDPendingRND', 'srfRnDInitialRND', 'srfRnDFinalRND', 'srfRnDCompletedRND', 'srfRnDReceivedRND', 'totalSRFCountRND', 'rndCrrOpen', 'rndRpeOpen', 'rndSrfOpen', 'totalOpenRND', 'rndCrrClosed', 'rndRpeClosed', 'rndSrfClosed', 'totalClosedRND', 'totalDueToday', 'crrDueToday', 'rpeDueToday' , 'srfDueToday', 'crrRndOpen'
        ));
    }

    public function RNDindex()
    {
        $userId = Auth::id(); 
        $userByUser = optional(Auth::user())->user_id; // Safely access user_id
        $role = optional(Auth::user())->role;
        
        if (!$userId && !$userByUser && !$role) {
            // Handle case where there is no authenticated user
            return redirect()->route('login'); // Or handle it in another appropriate way
        }
        return view('dashboard.rnd', compact('role'));
    }
}
