<?php

namespace App\Http\Controllers;

use App\Activity;
use App\CrrPersonnel;
use App\CustomerComplaint;
use App\CustomerComplaint2;
use App\CustomerFeedback;
use App\CustomerRequirement;
use App\CustomerSatisfaction;
use App\PriceMonitoring;
use App\Product;
use App\ProductEvaluation;
use App\SalesApprovers;
use App\RequestProductEvaluation;
use App\SampleRequest;
use App\Client;
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

        // $totalPRFCount = $prfSalesApproval + $prfWaiting + $prfReopened + $prfClosed + $prfManagerApproval

        // $primarySalesCount = CustomerRequirement::join('salesapprovers', 'users', 'customerrequirements.PrimarySalesPersonId', '=', 'salesapprovers.UserId')
        //             ->where('customerrequirements.Progress', '10') 
        //             ->where('customerrequirements.Status', '10') 
        //             ->where('salesapprovers.SalesApproverId', $userId) 
        //             ->count('customerrequirements.PrimarySalesPersonId'); 

        // dd($primarySalesCount);

        $crrSalesForApproval = CustomerRequirement::join('users', function($join) {
            $join->on('customerrequirements.PrimarySalesPersonId', '=', 'users.user_id')
                 ->orOn('customerrequirements.PrimarySalesPersonId', '=', 'users.id');
            }) 
            ->join('salesapprovers', 'users.id', '=', 'salesapprovers.UserId') 
            ->where('customerrequirements.Progress', '10') 
            ->where('customerrequirements.Status', '10') 
            ->where('salesapprovers.SalesApproverId', $userId)
            ->count(); 

        $rpeSalesForApproval = RequestProductEvaluation::join('users', function($join) {
                $join->on('requestproductevaluations.PrimarySalesPersonId', '=', 'users.user_id')
                     ->orOn('requestproductevaluations.PrimarySalesPersonId', '=', 'users.id');
            })
            ->join('salesapprovers', 'users.id', '=', 'salesapprovers.UserId')
            ->where('requestproductevaluations.Progress', '10')  // Filter by progress 10
            ->where('requestproductevaluations.Status', '10')    // Filter by status 10
            ->where(function($query) use ($userId) {
                // Ensure correct filtering by SalesApproverId for the logged-in user
                $query->where('salesapprovers.SalesApproverId', $userId)
                      ->whereNotNull('salesapprovers.SalesApproverId');
            })
            ->count(); // Correct count of rows based on the filters
        

        $srfSalesForApproval = SampleRequest::join('users', function($join) {
            $join->on('samplerequests.PrimarySalesPersonId', '=', 'users.user_id')
                    ->orOn('samplerequests.PrimarySalesPersonId', '=', 'users.id');
            }) 
            ->join('salesapprovers', 'users.id', '=', 'salesapprovers.UserId') 
            ->where('samplerequests.Progress', '10') 
            ->where('samplerequests.Status', '10') 
            ->where('salesapprovers.SalesApproverId', $userId)
            ->count();

        $prfSalesForApproval = PriceMonitoring::join('users', function($join) {
            $join->on('pricerequestforms.PrimarySalesPersonId', '=', 'users.user_id')
                    ->orOn('pricerequestforms.PrimarySalesPersonId', '=', 'users.id');
            }) 
            ->join('salesapprovers', 'users.id', '=', 'salesapprovers.UserId') 
            ->where('pricerequestforms.Progress', '10') 
            ->where('pricerequestforms.Status', '10') 
            ->where('salesapprovers.SalesApproverId', $userId)
            ->count();

        // Display the result
        // dd($prfSalesForApproval);

        // Sales Approval
        // function countApproval($model, $userId, $userByUser, $field, $value, $excludeField = null, $excludeValue = null) {
        //     return $model::where(function($query) use ($userId, $userByUser) {
        //                 $query->where('PrimarySalesPersonId', $userId)
        //                       ->orWhere('PrimarySalesPersonId', $userByUser);
        //             })
        //             ->where($field, $value)
        //             ->when($excludeField && $excludeValue, function ($query) use ($excludeField, $excludeValue) {
        //                 return $query->where($excludeField, '=', $excludeValue);
        //             })
        //             ->count();
        // }
        
        // Counting approvals for different models
        // $crrSalesForApproval = countApproval(CustomerRequirement::class, $userId, $userByUser, 'Progress', '10', 'Status', 10);
        // $rpeSalesForApproval = countApproval(RequestProductEvaluation::class, $userId, $userByUser, 'Progress', '10', 'Status', 10);
        // $srfSalesForApproval = countApproval(SampleRequest::class, $userId, $userByUser, 'Progress', '10', 'Status', 10);
        // $prfSalesForApproval = countApproval(PriceMonitoring::class, $userId, $userByUser, 'Progress', '10', 'Status', 10);

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

        $salesCrrReturn = CustomerRequirement::where('ReturnToSales', '1')
            ->where(function($query) use ($userId, $userByUser) {
                $query->where(function($query) use ($userId, $userByUser) {
                    $query->where('PrimarySalesPersonId', $userId)
                        ->orWhere('PrimarySalesPersonId', $userByUser);
                });
            })
            ->count();

        $salesRpeReturn = RequestProductEvaluation::where('ReturnToSales', '1')
            ->where(function($query) use ($userId, $userByUser) {
                $query->where(function($query) use ($userId, $userByUser) {
                    $query->where('PrimarySalesPersonId', $userId)
                        ->orWhere('PrimarySalesPersonId', $userByUser);
                });
            })
            ->count();

        $salesSrfReturn = SampleRequest::where('ReturnToSales', '1')
            ->where(function($query) use ($userId, $userByUser) {
                $query->where(function($query) use ($userId, $userByUser) {
                    $query->where('PrimarySalesPersonId', $userId)
                        ->orWhere('PrimarySalesPersonId', $userByUser);
                });
            })
            ->count();

        $totalReturned = $salesCrrReturn + $salesRpeReturn + $salesSrfReturn;

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


        $customerSatisfactionCount = CustomerSatisfaction::where('Status', '10')
            ->whereHas('clientCompany', function ($query) use ($userId, $userByUser) {
                $query->whereColumn('clientcompanies.Name', 'like', 'customersatisfaction.CompanyName')
                    ->where(function ($query) use ($userId, $userByUser) {
                        $query->where('clientcompanies.PrimaryAccountManagerId', $userId)
                            ->orWhere('clientcompanies.SecondaryAccountManagerId', $userId)
                            ->orWhere('clientcompanies.PrimaryAccountManagerId', $userByUser)
                            ->orWhere('clientcompanies.SecondaryAccountManagerId', $userByUser);
                    });
            })
            ->count();

        $customerComplaintCount = CustomerComplaint2::where('Status', '10')
            ->whereHas('clientCompany', function ($query) use ($userId, $userByUser) {
                // Use whereRaw for partial string matching with LIKE
                $query->whereRaw('LOWER(clientcompanies.Name) LIKE LOWER(CONCAT("%", customercomplaint.CompanyName, "%"))')
                    ->where(function ($query) use ($userId, $userByUser) {
                        $query->where('clientcompanies.PrimaryAccountManagerId', $userId)
                            ->orWhere('clientcompanies.SecondaryAccountManagerId', $userId)
                            ->orWhere('clientcompanies.PrimaryAccountManagerId', $userByUser)
                            ->orWhere('clientcompanies.SecondaryAccountManagerId', $userByUser);
                    });
            })
            
            ->count();

        $ccNotedBy = CustomerComplaint2::join('users', function($join) {
            $join->on('customercomplaint.ReceivedBy', '=', 'users.id');
            }) 
            ->join('salesapprovers', 'users.id', '=', 'salesapprovers.UserId') 
            ->where('customercomplaint.Progress', '20') 
            ->where('salesapprovers.SalesApproverId', $userId)
            ->count();

        $csNotedBy = CustomerSatisfaction::join('users', function($join) {
                $join->on('customersatisfaction.ReceivedBy', '=', 'users.user_id')
                     ->orOn('customersatisfaction.ReceivedBy', '=', 'users.id');
                }) 
                ->join('salesapprovers', 'users.id', '=', 'salesapprovers.UserId') 
                ->where('customersatisfaction.Progress', '20') 
                ->where('salesapprovers.SalesApproverId', $userId)
                ->count();
        
        $totalCs = $customerSatisfactionCount + $customerComplaintCount + $ccNotedBy + $csNotedBy;
    
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
            'crrRnDFinal', 'crrRnDCompleted', 'totalRPECount', 'rpeCancelled', 'rpeSalesApproval',
            'rpeSalesApproved', 'rpeSalesAccepted', 'rpeRnDOngoing', 'rpeRnDPending', 'rpeRnDInitial', 'rpeRnDFinal', 'rpeRnDCompleted', 'totalSRFCount', 'srfCancelled', 'srfSalesApproval',
            'srfSalesApproved', 'srfSalesAccepted', 'srfRnDOngoing', 'srfRnDPending', 'srfRnDInitial', 'srfRnDFinal', 'srfRnDCompleted', 'totalApproval', 'role', 'prfSalesApproval', 'totalPRFCount', 'prfSalesApproval', 'prfWaiting', 'prfReopened', 'prfClosed', 'prfManagerApproval', 'crrSalesForApproval', 'rpeSalesForApproval', 'srfSalesForApproval', 'prfSalesForApproval',  'newProducts', 'crrCancelledRND', 'crrSalesApprovalRND', 'crrSalesApprovedRND', 'crrSalesAcceptedRND', 'crrRnDOngoingRND', 'crrRnDPendingRND', 'crrRnDInitialRND', 'crrRnDFinalRND', 'crrRnDCompletedRND', 'crrRnDReceivedRND', 'totalCRRCountRND', 'rpeCancelledRND', 'rpeSalesApprovalRND', 'rpeSalesApprovedRND', 'rpeSalesAcceptedRND', 'rpeRnDOngoingRND', 'rpeRnDPendingRND', 'rpeRnDInitialRND', 'rpeRnDFinalRND', 'rpeRnDCompletedRND', 'rpeRnDReceivedRND', 'totalRPECountRND', 'srfCancelledRND', 'srfSalesApprovalRND', 'srfSalesApprovedRND', 'srfSalesAcceptedRND', 'srfRnDOngoingRND', 'srfRnDPendingRND', 'srfRnDInitialRND', 'srfRnDFinalRND', 'srfRnDCompletedRND', 'srfRnDReceivedRND', 'totalSRFCountRND', 'rndCrrClosed', 'rndRpeClosed', 'rndSrfClosed', 'totalClosedRND', 'salesCrrReturn', 'salesRpeReturn', 'salesSrfReturn', 'totalReturned', 'totalCs', 'customerSatisfactionCount', 'customerComplaintCount', 'ccNotedBy', 'csNotedBy'
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
        $crrRNDInitialReview = CustomerRequirement::where('Status', '10')->where('Progress', '57')->where('RefCode', 'RND')->count();
        $rpeRNDInitialReview = RequestProductEvaluation::where('Status', '10')->where('Progress', '57')->count();
        $srfRNDInitialReview = SampleRequest::where('Status', '10')->where('Progress', '57')->where('RefCode', '1')->count();

        $totalInitialReview = $crrRNDInitialReview + $rpeRNDInitialReview + $srfRNDInitialReview;

        $crrRNDFinalReview = CustomerRequirement::where('Status', '10')->where('Progress', '81')->where('RefCode', 'RND')->count();
        $rpeRNDFinalReview = RequestProductEvaluation::where('Status', '10')->where('Progress', '81')->count();
        $srfRNDFinalReview = SampleRequest::where('Status', '10')->where('Progress', '81')->where('RefCode', '1')->count();

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

        // CRR Transaction - QCD-WHI
        $crrImmediateOpen2 = CustomerRequirement::where('Status', '10')->where('RefCode', 'QCD-WHI')->count();
        $crrImmediateClosed2 = CustomerRequirement::where('Status', '30')->where('RefCode', 'QCD-WHI')->count();
        $crrImmediateCancelled2 = CustomerRequirement::where('Status', '50')->where('RefCode', 'QCD-WHI')->count();
        $totalCrrImmediate2 = $crrImmediateOpen2 + $crrImmediateClosed2 + $crrImmediateCancelled2;

        // CRR Transaction - QCD-PBI
        $crrImmediateOpen3 = CustomerRequirement::where('Status', '10')->where('RefCode', 'QCD-PBI')->count();
        $crrImmediateClosed3 = CustomerRequirement::where('Status', '30')->where('RefCode', 'QCD-PBI')->count();
        $crrImmediateCancelled3 = CustomerRequirement::where('Status', '50')->where('RefCode', 'QCD-PBI')->count();
        $totalCrrImmediate3 = $crrImmediateOpen3 + $crrImmediateClosed3 + $crrImmediateCancelled3;

        // CRR Transaction - QCD-MRDC
        $crrImmediateOpen4 = CustomerRequirement::where('Status', '10')->where('RefCode', 'QCD-MRDC')->count();
        $crrImmediateClosed4 = CustomerRequirement::where('Status', '30')->where('RefCode', 'QCD-MRDC')->count();
        $crrImmediateCancelled4 = CustomerRequirement::where('Status', '50')->where('RefCode', 'QCD-MRDC')->count();
        $totalCrrImmediate4 = $crrImmediateOpen4 + $crrImmediateClosed4 + $crrImmediateCancelled4;

        // CRR Transaction - QCD-CCC
        $crrImmediateOpen5 = CustomerRequirement::where('Status', '10')->where('RefCode', 'QCD-CCC')->count();
        $crrImmediateClosed5 = CustomerRequirement::where('Status', '30')->where('RefCode', 'QCD-CCC')->count();
        $crrImmediateCancelled5 = CustomerRequirement::where('Status', '50')->where('RefCode', 'QCD-CCC')->count();
        $totalCrrImmediate5 = $crrImmediateOpen5 + $crrImmediateClosed5 + $crrImmediateCancelled5;

        // SRF Transaction - QCD-WHI
        $srfImmediateOpen2 = SampleRequest::where('Status', '10')->where('RefCode', '2')->count();  
        $srfImmediateClosed2 = SampleRequest::where('Status', '30')->where('RefCode', '2')->count();  
        $srfImmediateCancelled2 = SampleRequest::where('Status', '50')->where('RefCode', '2')->count();
        $totalSrfImmediate2 = $srfImmediateOpen2 + $srfImmediateClosed2 + $srfImmediateCancelled2;

        // SRF Transaction - QCD-PBI
        $srfImmediateOpen3 = SampleRequest::where('Status', '10')->where('RefCode', '3')->count();
        $srfImmediateClosed3 = SampleRequest::where('Status', '30')->where('RefCode', '3')->count();  $srfImmediateCancelled3 = SampleRequest::where('Status', '50')->where('RefCode', '3')->count();
        $totalSrfImmediate3 = $srfImmediateOpen3 + $srfImmediateClosed3 + $srfImmediateCancelled3;

        // SRF Transaction - QCD-MRDC
        $srfImmediateOpen4 = SampleRequest::where('Status', '10')->where('RefCode', '4')->count(); 
        $srfImmediateClosed4 = SampleRequest::where('Status', '30')->where('RefCode', '4')->count(); 
        $srfImmediateCancelled4 = SampleRequest::where('Status', '50')->where('RefCode', '4')->count();
        $totalSrfImmediate4 = $srfImmediateOpen4 + $srfImmediateClosed4 + $srfImmediateCancelled4;

        // Open Transaction - QCD-CCC
        $srfImmediateOpen5 = SampleRequest::where('Status', '10')->where('RefCode', '5')->count();  
        $srfImmediateClosed5 = SampleRequest::where('Status', '30')->where('RefCode', '5')->count();  
        $srfImmediateCancelled5 = SampleRequest::where('Status', '50')->where('RefCode', '5')->count();
        $totalSrfImmediate5 = $srfImmediateOpen5 + $srfImmediateClosed5 + $srfImmediateCancelled5;

        // QCD Approval
        $crrQCDInitialReview2 = CustomerRequirement::where('Status', '10')->where('Progress', '57')->where('RefCode', 'QCD-WHI')->count();
        $srfQCDInitialReview2 = SampleRequest::where('Status', '10')->where('Progress', '57')->where('RefCode', '2')->count();
        $totalQCDInitialReview2 = $crrQCDInitialReview2 + $srfQCDInitialReview2;

        $crrQCDFinalReview2 = CustomerRequirement::where('Status', '10')->where('Progress', '81')->where('RefCode', 'QCD-WHI')->count();
        $srfQCDFinalReview2 = SampleRequest::where('Status', '10')->where('Progress', '81')->where('RefCode', '2')->count();
        $totalQCDFinalReview2 = $crrQCDFinalReview2 + $srfQCDFinalReview2;

        $crrQCDInitialReview3 = CustomerRequirement::where('Status', '10')->where('Progress', '57')->where('RefCode', 'QCD-PBI')->count();
        $srfQCDInitialReview3 = SampleRequest::where('Status', '10')->where('Progress', '57')->where('RefCode', '3')->count();
        $totalQCDInitialReview3 = $crrQCDInitialReview3 + $srfQCDInitialReview3;

        $crrQCDFinalReview3 = CustomerRequirement::where('Status', '10')->where('Progress', '81')->where('RefCode', 'QCD-PBI')->count();
        $srfQCDFinalReview3 = SampleRequest::where('Status', '10')->where('Progress', '81')->where('RefCode', '3')->count();
        $totalQCDFinalReview3 = $crrQCDFinalReview3 + $srfQCDFinalReview3;

        $crrQCDInitialReview4 = CustomerRequirement::where('Status', '10')->where('Progress', '57')->where('RefCode', 'QCD-MRDC')->count();
        $srfQCDInitialReview4 = SampleRequest::where('Status', '10')->where('Progress', '57')->where('RefCode', '4')->count();
        $totalQCDInitialReview4 = $crrQCDInitialReview4 + $srfQCDInitialReview4;

        $crrQCDFinalReview4 = CustomerRequirement::where('Status', '10')->where('Progress', '81')->where('RefCode', 'QCD-MRDC')->count();
        $srfQCDFinalReview4 = SampleRequest::where('Status', '10')->where('Progress', '81')->where('RefCode', '4')->count();
        $totalQCDFinalReview4 = $crrQCDFinalReview4 + $srfQCDFinalReview4;

        $crrQCDInitialReview5 = CustomerRequirement::where('Status', '10')->where('Progress', '57')->where('RefCode', 'QCD-CCC')->count();
        $srfQCDInitialReview5 = SampleRequest::where('Status', '10')->where('Progress', '57')->where('RefCode', '5')->count();
        $totalQCDInitialReview5 = $crrQCDInitialReview5 + $srfQCDInitialReview5;

        $crrQCDFinalReview5 = CustomerRequirement::where('Status', '10')->where('Progress', '81')->where('RefCode', 'QCD-CCC')->count();
        $srfQCDFinalReview5 = SampleRequest::where('Status', '10')->where('Progress', '81')->where('RefCode', '5')->count();
        $totalQCDFinalReview5 = $crrQCDFinalReview5 + $srfQCDFinalReview5;


        return view('dashboard.qcd', compact('role', 'crrQCD2New', 'srfQCD2New', 'totalQCD2New', 'crrQCD3New', 'srfQCD3New', 'totalQCD3New', 'crrQCD4New', 'srfQCD4New', 'totalQCD4New', 'crrQCD5New', 'srfQCD5New', 'totalQCD5New', 'crrDueToday2', 'srfDueToday2', 'totalDueToday2', 'crrDueToday3', 'srfDueToday3', 'totalDueToday3', 'crrDueToday4', 'srfDueToday4', 'totalDueToday4', 'crrDueToday5', 'srfDueToday5', 'totalDueToday5', 'crrImmediateOpen2', 'srfImmediateOpen2', 'crrImmediateOpen3', 'srfImmediateOpen3', 'crrImmediateOpen4', 'srfImmediateOpen4', 'crrImmediateOpen5', 'srfImmediateOpen5', 'crrImmediateClosed2', 'srfImmediateClosed2', 'crrImmediateClosed3', 'srfImmediateClosed3', 'crrImmediateClosed4', 'srfImmediateClosed4', 'crrImmediateClosed5', 'srfImmediateClosed5', 'crrImmediateCancelled2', 'totalCrrImmediate2', 'crrImmediateCancelled3', 'totalCrrImmediate3', 'crrImmediateCancelled4', 'totalCrrImmediate4', 'crrImmediateCancelled5', 'totalCrrImmediate5', 'srfImmediateCancelled2', 'totalSrfImmediate2', 'srfImmediateCancelled3', 'totalSrfImmediate3', 'srfImmediateCancelled4', 'totalSrfImmediate4', 'srfImmediateCancelled5', 'totalSrfImmediate5', 'totalQCDInitialReview2', 'crrQCDInitialReview2', 'srfQCDInitialReview2', 'totalQCDFinalReview2', 'crrQCDFinalReview2', 'srfQCDFinalReview2', 'totalQCDInitialReview3', 'crrQCDInitialReview3', 'srfQCDInitialReview3', 'totalQCDFinalReview3', 'crrQCDFinalReview3', 'srfQCDFinalReview3', 'totalQCDInitialReview4', 'crrQCDInitialReview4', 'srfQCDInitialReview4', 'totalQCDFinalReview4', 'crrQCDFinalReview4', 'srfQCDFinalReview4', 'totalQCDInitialReview5', 'crrQCDInitialReview5', 'srfQCDInitialReview5', 'totalQCDFinalReview5', 'crrQCDFinalReview5', 'srfQCDFinalReview5'));
    }

    public function returned(Request $request)
    {   
        $entries = $request->input('entries', 10); // Default to 10 if not specified
        $search = $request->input('search');

        $userId = Auth::id(); 
        $userByUser = optional(Auth::user())->user_id; // Safely access user_id

        $crrReturned = CustomerRequirement::where('ReturnToSales', '1')
                        ->when($search, function($query) use ($search) {
                            $query->where('ClientId', 'LIKE', "%{$search}%")
                                ->orWhere('CrrNumber', 'LIKE', "%{$search}%")
                                ->orWhere('ApplicationId', 'LIKE', "%{$search}%")
                                ->orWhere('Status', 'LIKE', "%{$search}%");
                        })
                        ->where(function ($query) use ($userId, $userByUser) {
                            $query->where('PrimarySalesPersonId', $userId)
                                ->orWhere('SecondarySalesPersonId', $userId)
                                ->orWhere('PrimarySalesPersonId', $userByUser)
                                ->orWhere('SecondarySalesPersonId', $userByUser);
                        })
                        ->paginate($entries, ['*'], 'crr_page')
                        ->appends(['search' => $search, 'entries' => $entries]);
        
        $rpeReturned = RequestProductEvaluation::where('ReturnToSales', '1')
                        ->when($search, function($query) use ($search) {
                            $query->where('ClientId', 'LIKE', "%{$search}%")
                                ->orWhere('RpeNumber', 'LIKE', "%{$search}%")
                                ->orWhere('ApplicationId', 'LIKE', "%{$search}%")
                                ->orWhere('Status', 'LIKE', "%{$search}%");
                        })
                        ->where(function ($query) use ($userId, $userByUser) {
                            $query->where('PrimarySalesPersonId', $userId)
                                ->orWhere('SecondarySalesPersonId', $userId)
                                ->orWhere('PrimarySalesPersonId', $userByUser)
                                ->orWhere('SecondarySalesPersonId', $userByUser);
                        })
                        ->paginate($entries, ['*'], 'rpe_page')
                        ->appends(['search' => $search, 'entries' => $entries]);

        $srfReturned = SampleRequest::where('ReturnToSales', '1')
                        ->when($search, function($query) use ($search) {
                            $query->where('ClientId', 'LIKE', "%{$search}%")
                                ->orWhere('SrfNumber', 'LIKE', "%{$search}%")
                                ->orWhere('ApplicationId', 'LIKE', "%{$search}%")
                                ->orWhere('Status', 'LIKE', "%{$search}%");
                        })
                        ->where(function ($query) use ($userId, $userByUser) {
                            $query->where('PrimarySalesPersonId', $userId)
                                ->orWhere('SecondarySalesPersonId', $userId)
                                ->orWhere('PrimarySalesPersonId', $userByUser)
                                ->orWhere('SecondarySalesPersonId', $userByUser);
                        })
                        ->paginate($entries, ['*'], 'srf_page')
                        ->appends(['search' => $search, 'entries' => $entries]);

        return view('dashboard.return_transactions', compact('entries', 'search', 'crrReturned', 'rpeReturned', 'srfReturned'));
    }
}
