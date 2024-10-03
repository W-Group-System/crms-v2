<?php

namespace App\Http\Controllers;

use App\Activity;
use App\CrrPersonnel;
use App\CustomerComplaint;
use App\CustomerFeedback;
use App\CustomerRequirement;
use App\PriceMonitoring;
use App\Product;
use App\ProductEvaluation;
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
        if ($role && $role->type == 'RND' || $role->type == 'ITD' || $role->type == 'ACCTG') {
            return redirect('/dashboard-rnd');
        } elseif ($role && $role->type == 'IS' || $role->type == 'LS') {
            return redirect('/dashboard-sales');
        } elseif ($role && $role->type == 'QCD-WHI' || $role->type == 'QCD-PBI' || $role->type == 'QCD-MRDC' || $role->type == 'QCD-CCC') {
            return redirect('/dashboard-qcd');
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

        // Open Transactions
        $salesCrrOpen = CustomerRequirement::where('Status', '10')
            ->where(function($query) use ($userId, $userByUser) {
                $query->where(function($query) use ($userId, $userByUser) {
                    $query->where('PrimarySalesPersonId', $userId)
                        ->orWhere('SecondarySalesPersonId', $userId)
                        ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                });
            })
            ->count(); 
        
        $salesRpeOpen = RequestProductEvaluation::where('Status', '10')
            ->where(function($query) use ($userId, $userByUser) {
                $query->where(function($query) use ($userId, $userByUser) {
                    $query->where('PrimarySalesPersonId', $userId)
                        ->orWhere('SecondarySalesPersonId', $userId)
                        ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                });
            })
            ->count();
        
        $salesSrfOpen = SampleRequest::where('Status', '10')
            ->where(function($query) use ($userId, $userByUser) {
                $query->where(function($query) use ($userId, $userByUser) {
                    $query->where('PrimarySalesPersonId', $userId)
                        ->orWhere('SecondarySalesPersonId', $userId)
                        ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                });
            })
            ->count();
        
        $salesPrfOpen = PriceMonitoring::where('Status', '10')
            ->where(function($query) use ($userId, $userByUser) {
                $query->where(function($query) use ($userId, $userByUser) {
                    $query->where('PrimarySalesPersonId', $userId)
                        ->orWhere('SecondarySalesPersonId', $userId)
                        ->orWhere('PrimarySalesPersonId', $userByUser)
                        ->orWhere('SecondarySalesPersonId', $userByUser);
                });
            })
            ->count();

        $totalSalesOpen = $salesCrrOpen + $salesRpeOpen + $salesSrfOpen + $salesPrfOpen;

        // CRR Transactions
        function getCustomerRequirementCount($status, $progress = null, $userId, $userByUser) {
            return CustomerRequirement::where('Status', $status)
                ->when($progress, function($query) use ($progress) {
                    $query->where('Progress', $progress);
                })
                ->where(function($query) use ($userId, $userByUser) {
                    $query->where(function($query) use ($userId, $userByUser) {
                        $query->where('PrimarySalesPersonId', $userId)
                            ->orWhere('SecondarySalesPersonId', $userId)
                            ->orWhere('PrimarySalesPersonId', $userByUser)
                            ->orWhere('SecondarySalesPersonId', $userByUser);
                    });
                })
                ->count();
        }
        $salesCrrClosed = getCustomerRequirementCount(30, null, $userId, $userByUser);
        $salesCrrCancelled = getCustomerRequirementCount(50, null, $userId, $userByUser);
        $salesCrrApproval = getCustomerRequirementCount(10, 10, $userId, $userByUser);
        $salesCrrApproved = getCustomerRequirementCount(10, 20, $userId, $userByUser);
        $salesCrrAccepted = getCustomerRequirementCount(10, 70, $userId, $userByUser);

        $totalSalesCRR = $salesCrrClosed + $salesCrrCancelled + $salesCrrApproval + $salesCrrApproved + $salesCrrAccepted;

        // RPE Transactions
        function getRequestProductCount($status, $progress = null, $userId, $userByUser) {
            return RequestProductEvaluation::where('Status', $status)
                ->when($progress, function($query) use ($progress) {
                    $query->where('Progress', $progress);
                })
                ->where(function($query) use ($userId, $userByUser) {
                    $query->where(function($query) use ($userId, $userByUser) {
                        $query->where('PrimarySalesPersonId', $userId)
                            ->orWhere('SecondarySalesPersonId', $userId)
                            ->orWhere('PrimarySalesPersonId', $userByUser)
                            ->orWhere('SecondarySalesPersonId', $userByUser);
                    });
                })
                ->count();
        }
        $salesRpeClosed = getRequestProductCount(30, null, $userId, $userByUser);
        $salesRpeCancelled = getRequestProductCount(50, null, $userId, $userByUser);
        $salesRpeApproval = getRequestProductCount(10, 10, $userId, $userByUser);
        $salesRpeApproved = getRequestProductCount(10, 20, $userId, $userByUser);
        $salesRpeAccepted = getRequestProductCount(10, 70, $userId, $userByUser);

        $totalSalesRPE = $salesRpeClosed + $salesRpeCancelled + $salesRpeApproval + $salesRpeApproved + $salesRpeAccepted;

        // SRF Transactions
        function getSampleRequestCount($status, $progress = null, $userId, $userByUser) {
            return SampleRequest::where('Status', $status)
                ->when($progress, function($query) use ($progress) {
                    $query->where('Progress', $progress);
                })
                ->where(function($query) use ($userId, $userByUser) {
                    $query->where(function($query) use ($userId, $userByUser) {
                        $query->where('PrimarySalesPersonId', $userId)
                            ->orWhere('SecondarySalesPersonId', $userId)
                            ->orWhere('PrimarySalesPersonId', $userByUser)
                            ->orWhere('SecondarySalesPersonId', $userByUser);
                    });
                })
                ->count();
        }
        $salesSrfClosed = getSampleRequestCount(30, null, $userId, $userByUser);
        $salesSrfCancelled = getSampleRequestCount(50, null, $userId, $userByUser);
        $salesSrfApproval = getSampleRequestCount(10, 10, $userId, $userByUser);
        $salesSrfApproved = getSampleRequestCount(10, 20, $userId, $userByUser);
        $salesSrfAccepted = getSampleRequestCount(10, 70, $userId, $userByUser);

        $totalSalesSRF = $salesSrfClosed + $salesSrfCancelled + $salesSrfApproval + $salesSrfApproved + $salesSrfAccepted;

        // PRF Transactions
        function getPriceMonitoringCount($status, $progress = null, $userId, $userByUser) {
            return PriceMonitoring::where('Status', $status)
                ->when($progress, function($query) use ($progress) {
                    $query->where('Progress', $progress);
                })
                ->where(function($query) use ($userId, $userByUser) {
                    $query->where(function($query) use ($userId, $userByUser) {
                        $query->where('PrimarySalesPersonId', $userId)
                            ->orWhere('SecondarySalesPersonId', $userId)
                            ->orWhere('PrimarySalesPersonId', $userByUser)
                            ->orWhere('SecondarySalesPersonId', $userByUser);
                    });
                })
                ->count();
        }
        $salesPrfClosed = getPriceMonitoringCount(30, null, $userId, $userByUser);
        $salesPrfReopened = getPriceMonitoringCount(10, 25, $userId, $userByUser);
        $salesPrfApproval = getPriceMonitoringCount(10, 10, $userId, $userByUser);
        $salesPrfWaiting = getPriceMonitoringCount(10, 20, $userId, $userByUser);
        $salesPrfManager = getPriceMonitoringCount(10, 40, $userId, $userByUser);

        $totalSalesPRF = $salesPrfClosed + $salesPrfReopened + $salesPrfApproval + $salesPrfWaiting + $salesPrfManager;

        // Customer Service
        function getCustomerServiceCount($model, $status, $type, $userId, $userByUser) {
            return $model::where('Status', $status)
                ->where('Type', $type)
                ->where(function($query) use ($userId, $userByUser) {
                    $query->where('ReceivedByUserId', $userId)
                        ->orWhere('ReceivedByUserId', $userByUser);
                })
                ->count();
        }
        $customerComplaintsCount = getCustomerServiceCount(CustomerComplaint::class, 10, 20, $userId, $userByUser);
        $customerFeedbackCount = getCustomerServiceCount(CustomerFeedback::class, 10, 30, $userId, $userByUser);

        $totalCustomerServiceCount = $customerComplaintsCount + $customerFeedbackCount;


        /************* RND *************/
    

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

        return view('dashboard.index', compact(
            'totalActivitiesCount', 'openActivitiesCount', 'closedActivitiesCount', 'salesCrrOpen', 'salesRpeOpen', 'salesSrfOpen', 'salesPrfOpen', 'totalSalesOpen', 'salesCrrClosed', 'salesCrrCancelled', 'salesCrrApproval', 'salesCrrApproved', 'salesCrrAccepted', 'totalSalesCRR', 'totalSalesRPE', 'salesRpeClosed', 'salesRpeCancelled', 'salesRpeApproval', 'salesRpeApproved', 'salesRpeAccepted', 'totalSalesSRF', 'salesSrfClosed', 'salesSrfCancelled', 'salesSrfApproval', 'salesSrfApproved', 'salesSrfAccepted', 'totalSalesPRF', 'salesPrfClosed', 'salesPrfReopened', 'salesPrfApproval', 'salesPrfWaiting', 'salesPrfManager',
            'totalCRRCount', 'crrCancelled', 'crrSalesAccepted', 'crrSalesApproval', 
            'crrSalesApproved', 'crrRnDOngoing', 'crrRnDPending', 'crrRnDInitial',
            'crrRnDFinal', 'crrRnDCompleted', 'totalCustomerServiceCount', 
            'customerComplaintsCount', 'customerFeedbackCount', 'totalRPECount', 'rpeCancelled', 'rpeSalesApproval',
            'rpeSalesApproved', 'rpeSalesAccepted', 'rpeRnDOngoing', 'rpeRnDPending', 'rpeRnDInitial', 'rpeRnDFinal', 'rpeRnDCompleted', 'totalSRFCount', 'srfCancelled', 'srfSalesApproval',
            'srfSalesApproved', 'srfSalesAccepted', 'srfRnDOngoing', 'srfRnDPending', 'srfRnDInitial', 'srfRnDFinal', 'srfRnDCompleted', 'totalApproval', 'role', 'prfSalesApproval', 'totalPRFCount', 'prfSalesApproval', 'prfWaiting', 'prfReopened', 'prfClosed', 'prfManagerApproval', 'crrSalesForApproval', 'rpeSalesForApproval', 'srfSalesForApproval', 'prfSalesForApproval',  'newProducts', 'crrCancelledRND', 'crrSalesApprovalRND', 'crrSalesApprovedRND', 'crrSalesAcceptedRND', 'crrRnDOngoingRND', 'crrRnDPendingRND', 'crrRnDInitialRND', 'crrRnDFinalRND', 'crrRnDCompletedRND', 'crrRnDReceivedRND', 'totalCRRCountRND', 'rpeCancelledRND', 'rpeSalesApprovalRND', 'rpeSalesApprovedRND', 'rpeSalesAcceptedRND', 'rpeRnDOngoingRND', 'rpeRnDPendingRND', 'rpeRnDInitialRND', 'rpeRnDFinalRND', 'rpeRnDCompletedRND', 'rpeRnDReceivedRND', 'totalRPECountRND', 'srfCancelledRND', 'srfSalesApprovalRND', 'srfSalesApprovedRND', 'srfSalesAcceptedRND', 'srfRnDOngoingRND', 'srfRnDPendingRND', 'srfRnDInitialRND', 'srfRnDFinalRND', 'srfRnDCompletedRND', 'srfRnDReceivedRND', 'totalSRFCountRND', 'rndCrrClosed', 'rndRpeClosed', 'rndSrfClosed', 'totalClosedRND', 
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

        // RND Approval
        $crrRNDInitialReview = CustomerRequirement::where('Status', '10')->where('Progress', '57')->count();
        $rpeRNDInitialReview = RequestProductEvaluation::where('Status', '10')->where('Progress', '57')->count();
        $srfRNDInitialReview = SampleRequest::where('Status', '10')->where('Progress', '57')->count();

        $totalInitialReview = $crrRNDInitialReview + $rpeRNDInitialReview + $srfRNDInitialReview;

        $crrRNDFinalReview = CustomerRequirement::where('Status', '10')->where('Progress', '81')->count();
        $rpeRNDFinalReview = RequestProductEvaluation::where('Status', '10')->where('Progress', '81')->count();
        $srfRNDFinalReview = SampleRequest::where('Status', '10')->where('Progress', '81')->count();

        $totalFinalReview = $crrRNDFinalReview + $rpeRNDFinalReview + $srfRNDFinalReview;

        // RND New Request
        $crrRNDNew = CustomerRequirement::where('Status', '10')->where('Progress', '30')->where('RefCode', 'RND')->count();
        $rpeRNDNew = RequestProductEvaluation::where('Status', '10')->where('Progress', '30')->count();
        $srfRNDNew = SampleRequest::where('Status', '10')->where('Progress', '30')->where('RefCode', '1')->count();

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
        $crrImmediateOpen = CustomerRequirement::where('Status', '10')->count();
        $rpeImmediateOpen = RequestProductEvaluation::where('Status', '10')->count();
        $srfImmediateOpen = SampleRequest::where('Status', '10')->count();     

        // Closed Transaction
        $crrImmediateClosed = CustomerRequirement::where('Status', '30')->count();
        $rpeImmediateClosed = RequestProductEvaluation::where('Status', '30')->count();
        $srfImmediateClosed = SampleRequest::where('Status', '30')->count();

        // Cancelled Transaction
        $crrImmediateCancelled = CustomerRequirement::where('Status', '50')->count();
        $rpeImmediateCancelled = RequestProductEvaluation::where('Status', '50')->count();
        $srfImmediateCancelled = SampleRequest::where('Status', '50')->count();

        $totalImmediateCRR = $crrImmediateOpen + $crrImmediateClosed + $crrImmediateCancelled;
        $totalImmediateRPE = $rpeImmediateOpen + $rpeImmediateClosed + $srfImmediateCancelled;
        $totalImmediateSRF = $srfImmediateOpen + $srfImmediateClosed + $srfImmediateCancelled;

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

        // // CRR counts RND
        // function countCustomerRequirementsRND($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
        //     return CustomerRequirement::whereIn('id', function($query) use ($userId, $userByUser) {
        //                 $query->select('CustomerRequirementId')
        //                     ->from('crrpersonnels')
        //                     ->where('PersonnelUserId', $userId)
        //                     ->orWhere('PersonnelUserId', $userByUser);
        //             })
        //             ->where($field, $value)
        //             ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
        //                 return $query->where($excludeField, '!=', $excludeValue); // Exclude records where this condition matches
        //             })
        //             ->count();
        // }

        // $crrCancelledRND = countCustomerRequirementsRND($userId, $userByUser, 'Status', '50', );
        // $crrSalesApprovalRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '10', 'Status', 10);
        // $crrSalesApprovedRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '20');
        // $crrSalesAcceptedRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '70');
        // $crrRnDReceivedRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '35');
        // $crrRnDOngoingRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '50');
        // $crrRnDPendingRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '55');
        // $crrRnDInitialRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '57');
        // $crrRnDFinalRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '81');
        // $crrRnDCompletedRND = countCustomerRequirementsRND($userId, $userByUser, 'Progress', '60');
        // $totalCRRCountRND = $crrCancelledRND + $crrSalesApprovalRND + $crrSalesApprovedRND + $crrSalesAcceptedRND + $crrRnDReceivedRND + $crrRnDOngoingRND + $crrRnDPendingRND + $crrRnDInitialRND + $crrRnDFinalRND + $crrRnDCompletedRND;

        // // RPE counts RND
        // function countProductEvaluationRND($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
        //     return RequestProductEvaluation::whereIn('id', function($query) use ($userId, $userByUser) {
        //                 $query->select('RequestProductEvaluationId')
        //                     ->from('rpepersonnels')
        //                     ->where('PersonnelUserId', $userId)
        //                     ->orWhere('PersonnelUserId', $userByUser);
        //             })
        //             ->where($field, $value)
        //             ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
        //                 return $query->where($excludeField, '!=', $excludeValue); // Exclude records where this condition matches
        //             })
        //             ->count();
        // }

        // $rpeCancelledRND = countProductEvaluationRND($userId, $userByUser, 'Status', '50', );
        // $rpeSalesApprovalRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '10', 'Status', 10);
        // $rpeSalesApprovedRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '20');
        // $rpeSalesAcceptedRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '70');
        // $rpeRnDReceivedRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '35');
        // $rpeRnDOngoingRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '50');
        // $rpeRnDPendingRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '55');
        // $rpeRnDInitialRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '57');
        // $rpeRnDFinalRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '81');
        // $rpeRnDCompletedRND = countProductEvaluationRND($userId, $userByUser, 'Progress', '60');
        // $totalRPECountRND = $rpeCancelledRND + $rpeSalesApprovalRND + $rpeSalesApprovedRND + $rpeSalesAcceptedRND + $rpeRnDReceivedRND + $rpeRnDOngoingRND + $rpeRnDPendingRND + $rpeRnDInitialRND + $rpeRnDFinalRND + $rpeRnDCompletedRND;

        // // SRF counts RND
        // function countSampleRequestRND($userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
        //     return SampleRequest::whereIn('id', function($query) use ($userId, $userByUser) {
        //                 $query->select('SampleRequestId')
        //                     ->from('srfpersonnels')
        //                     ->where('PersonnelUserId', $userId)
        //                     ->orWhere('PersonnelUserId', $userByUser);
        //             })
        //             ->where($field, $value)
        //             ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
        //                 return $query->where($excludeField, '!=', $excludeValue); // Exclude records where this condition matches
        //             })
        //             ->count();
        // }

        // $srfCancelledRND = countSampleRequestRND($userId, $userByUser, 'Status', '50', );
        // $srfSalesApprovalRND = countSampleRequestRND($userId, $userByUser, 'Progress', '10', 'Status', 10);
        // $srfSalesApprovedRND = countSampleRequestRND($userId, $userByUser, 'Progress', '20');
        // $srfSalesAcceptedRND = countSampleRequestRND($userId, $userByUser, 'Progress', '70');
        // $srfRnDReceivedRND = countSampleRequestRND($userId, $userByUser, 'Progress', '35');
        // $srfRnDOngoingRND = countSampleRequestRND($userId, $userByUser, 'Progress', '50');
        // $srfRnDPendingRND = countSampleRequestRND($userId, $userByUser, 'Progress', '55');
        // $srfRnDInitialRND = countSampleRequestRND($userId, $userByUser, 'Progress', '57');
        // $srfRnDFinalRND = countSampleRequestRND($userId, $userByUser, 'Progress', '81');
        // $srfRnDCompletedRND = countSampleRequestRND($userId, $userByUser, 'Progress', '60');
        // $totalSRFCountRND = $srfCancelledRND + $srfSalesApprovalRND + $srfSalesApprovedRND + $srfSalesAcceptedRND + $srfRnDReceivedRND + $srfRnDOngoingRND + $srfRnDPendingRND + $srfRnDInitialRND + $srfRnDFinalRND + $srfRnDCompletedRND;

        return view('dashboard.rnd', compact('role', 'newProducts', 'crrRNDInitialReview', 'rpeRNDInitialReview', 'srfRNDInitialReview', 'totalInitialReview', 'crrRNDFinalReview', 'rpeRNDFinalReview', 'srfRNDFinalReview', 'totalFinalReview', 'crrRNDNew', 'rpeRNDNew', 'srfRNDNew', 'totalNewRequest', 'crrDue', 'rpeDue', 'srfDue', 'totalDue', 'totalDueToday', 'crrDueToday', 'rpeDueToday' , 'srfDueToday', 'totalOpenRND', 'rndCrrOpen', 'rndRpeOpen', 'rndSrfOpen', 'totalClosedRND', 'rndCrrClosed', 'rndRpeClosed', 'rndSrfClosed', 'crrImmediateOpen', 'rpeImmediateOpen', 'srfImmediateOpen', 'crrImmediateClosed', 'rpeImmediateClosed', 'srfImmediateClosed', 'crrImmediateCancelled', 'rpeImmediateCancelled', 'srfImmediateCancelled', 'totalImmediateCRR', 'totalImmediateRPE', 'totalImmediateSRF'));
    }

    public function qcdIndex()
    {
        $userId = Auth::id(); 
        $userByUser = optional(Auth::user())->user_id; // Safely access user_id
        $role = optional(Auth::user())->role;
        
        if (!$userId && !$userByUser && !$role) {
            // Handle case where there is no authenticated user
            return redirect()->route('login'); // Or handle it in another appropriate way
        }

        // New Request - QCD-WHI
        $crrQCD2New = CustomerRequirement::where('Status', '10')->where('Progress', '30')->where('RefCode', 'QCD-WHI')->count();
        $srfQCD2New = SampleRequest::where('Status', '10')->where('Progress', '30')->where('RefCode', '2')->count();
        $totalQCD2New = $crrQCD2New + $srfQCD2New;

        // New Request - QCD-PBI
        $crrQCD3New = CustomerRequirement::where('Status', '10')->where('Progress', '30')->where('RefCode', 'QCD-PBI')->count();
        $srfQCD3New = SampleRequest::where('Status', '10')->where('Progress', '30')->where('RefCode', '3')->count();
        $totalQCD3New = $crrQCD3New + $srfQCD3New;

        // New Request - QCD-MRDC
        $crrQCD4New = CustomerRequirement::where('Status', '10')->where('Progress', '30')->where('RefCode', 'QCD-MRDC')->count();
        $srfQCD4New = SampleRequest::where('Status', '10')->where('Progress', '30')->where('RefCode', '4')->count();
        $totalQCD4New = $crrQCD4New + $srfQCD4New;

        // New Request - QCD-CCC
        $crrQCD5New = CustomerRequirement::where('Status', '10')->where('Progress', '30')->where('RefCode', 'QCD-CCC')->count();
        $srfQCD5New = SampleRequest::where('Status', '10')->where('Progress', '30')->where('RefCode', '5')->count();
        $totalQCD5New = $crrQCD5New + $srfQCD5New;


        // Due Today - QCD-WHI 
        $crrDueToday2 = CustomerRequirement::where('Status', '10')->where('DueDate', '<', now())->where('RefCode', 'QCD-WHI')->count();
        $srfDueToday2 = SampleRequest::where('Status', '10')->where('DateRequired', '<', now())->where('RefCode', '2')->count();
        $totalDueToday2 = $crrDueToday2 + $srfDueToday2;

        // Due Today - QCD-PBI 
        $crrDueToday3 = CustomerRequirement::where('Status', '10')->where('DueDate', '<', now())->where('RefCode', 'QCD-PBI')->count();
        $srfDueToday3 = SampleRequest::where('Status', '10')->where('DateRequired', '<', now())->where('RefCode', '3')->count();
        $totalDueToday3 = $crrDueToday3 + $srfDueToday3;

        // Due Today - QCD-MRDC 
        $crrDueToday4 = CustomerRequirement::where('Status', '10')->where('DueDate', '<', now())->where('RefCode', 'QCD-MRDC')->count();
        $srfDueToday4 = SampleRequest::where('Status', '10')->where('DateRequired', '<', now())->where('RefCode', '4')->count();
        $totalDueToday4 = $crrDueToday4 + $srfDueToday4;

        // Due Today - QCD-CCC 
        $crrDueToday5 = CustomerRequirement::where('Status', '10')->where('DueDate', '<', now())->where('RefCode', 'QCD-CCC')->count();
        $srfDueToday5 = SampleRequest::where('Status', '10')->where('DateRequired', '<', now())->where('RefCode', '5')->count();
        $totalDueToday5 = $crrDueToday5 + $srfDueToday5;

        // Open Transaction - QCD-WHI
        $crrImmediateOpen2 = CustomerRequirement::where('Status', '10')->where('RefCode', 'QCD-WHI')->count();
        $srfImmediateOpen2 = SampleRequest::where('Status', '10')->where('RefCode', '2')->count();  
        $totalImmediateOpen2 = $crrImmediateOpen2 + $srfImmediateOpen2;

        // Open Transaction - QCD-PBI
        $crrImmediateOpen3 = CustomerRequirement::where('Status', '10')->where('RefCode', 'QCD-PBI')->count();
        $srfImmediateOpen3 = SampleRequest::where('Status', '10')->where('RefCode', '3')->count();  
        $totalImmediateOpen3 = $crrImmediateOpen3 + $srfImmediateOpen3;

        // Open Transaction - QCD-MRDC
        $crrImmediateOpen4 = CustomerRequirement::where('Status', '10')->where('RefCode', 'QCD-MRDC')->count();
        $srfImmediateOpen4 = SampleRequest::where('Status', '10')->where('RefCode', '4')->count();  
        $totalImmediateOpen4 = $crrImmediateOpen4 + $srfImmediateOpen4;

        // Open Transaction - QCD-CCC
        $crrImmediateOpen5 = CustomerRequirement::where('Status', '10')->where('RefCode', 'QCD-CCC')->count();
        $srfImmediateOpen5 = SampleRequest::where('Status', '10')->where('RefCode', '5')->count();  
        $totalImmediateOpen5 = $crrImmediateOpen5 + $srfImmediateOpen5;


        // Closed Transaction - QCD-WHI
        $crrImmediateClosed2 = CustomerRequirement::where('Status', '30')->where('RefCode', 'QCD-WHI')->count();
        $srfImmediateClosed2 = SampleRequest::where('Status', '30')->where('RefCode', '2')->count();  
        $totalImmediateClosed2 = $crrImmediateClosed2 + $srfImmediateClosed2;

        // Closed Transaction - QCD-PBI
        $crrImmediateClosed3 = CustomerRequirement::where('Status', '30')->where('RefCode', 'QCD-PBI')->count();
        $srfImmediateClosed3 = SampleRequest::where('Status', '30')->where('RefCode', '3')->count();  
        $totalImmediateClosed3 = $crrImmediateClosed3 + $srfImmediateClosed3;

        // Closed Transaction - QCD-MRDC
        $crrImmediateClosed4 = CustomerRequirement::where('Status', '30')->where('RefCode', 'QCD-MRDC')->count();
        $srfImmediateClosed4 = SampleRequest::where('Status', '30')->where('RefCode', '4')->count();  
        $totalImmediateClosed4 = $crrImmediateClosed4 + $srfImmediateClosed4;

        // Closed Transaction - QCD-CCC
        $crrImmediateClosed5 = CustomerRequirement::where('Status', '30')->where('RefCode', 'QCD-CCC')->count();
        $srfImmediateClosed5 = SampleRequest::where('Status', '30')->where('RefCode', '5')->count();  
        $totalImmediateClosed5 = $crrImmediateClosed5 + $srfImmediateClosed5;

        return view('dashboard.qcd', compact('role', 'crrQCD2New', 'srfQCD2New', 'totalQCD2New', 'crrQCD3New', 'srfQCD3New', 'totalQCD3New', 'crrQCD4New', 'srfQCD4New', 'totalQCD4New', 'crrQCD5New', 'srfQCD5New', 'totalQCD5New', 'crrDueToday2', 'srfDueToday2', 'totalDueToday2', 'crrDueToday3', 'srfDueToday3', 'totalDueToday3', 'crrDueToday4', 'srfDueToday4', 'totalDueToday4', 'crrDueToday5', 'srfDueToday5', 'totalDueToday5', 'totalImmediateOpen2', 'crrImmediateOpen2', 'srfImmediateOpen2', 'totalImmediateOpen3', 'crrImmediateOpen3', 'srfImmediateOpen3', 'totalImmediateOpen4', 'crrImmediateOpen4', 'srfImmediateOpen4', 'totalImmediateOpen5', 'crrImmediateOpen5', 'srfImmediateOpen5', 'totalImmediateClosed2', 'crrImmediateClosed2', 'srfImmediateClosed2', 'totalImmediateClosed3', 'crrImmediateClosed3', 'srfImmediateClosed3', 'totalImmediateClosed4', 'crrImmediateClosed4', 'srfImmediateClosed4', 'totalImmediateClosed5', 'crrImmediateClosed5', 'srfImmediateClosed5'));
    }
}
