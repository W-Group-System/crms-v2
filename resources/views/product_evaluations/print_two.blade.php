<!DOCTYPE html>
<html>
<head>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: right;
            margin-bottom: 10px;
        }

        .section-title {
            background-color: #d3d3d3;
            padding: 5px;
            margin-top: 10px;
            font-weight: bold;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table td {
            border: none;
            vertical-align: middle; 
        }

        .label {
            font-weight: bold;
            text-align: right;
            width: 50%;
        }

        .detail {
            text-align: left; 
            padding-left: 10px;
            width: 50%; 
        }
        .signatures {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding-top: 50px;
            text-align: center;
        }

        .signature-space {
            width: 33%;
            display: inline-block;
            text-align: center;
            margin-top: 50px;
        }

        .page {
            position: relative;
            /* min-height: 100vh;  */
            padding-bottom: 100px; 
        }

        .two-columns {
            display: table;
            width: 100%;
        }

        .column {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        .border {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 10px;
        overflow: hidden; 
        }
        .form-divider{
        border-top: 3px solid rgb(0, 13, 255);
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <p>{{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
        </div>
        <h3>REQUEST PRODUCT EVALUATION</h3>
        <hr class="form-divider">

        <div class="section-title">Customer Details</div>
        <div class="two-columns">
            <div class="column">
                <table class="details-table">
                    <tr>
                        <td class="label"><span>Client Name:</span></td>
                        <td class="detail">{{ optional($product_evaluations->client)->Name }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Client Trade Name:</span></td>
                        <td class="detail">{{ optional($product_evaluations->client)->trade_name }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Region:</span></td>
                        <td class="detail">{{ optional(optional($product_evaluations->client)->clientregion)->Name }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Country:</span></td>
                        <td class="detail">{{ optional(optional($product_evaluations->client)->clientcountry)->Name }}</td>
                    </tr>
                </table>
            </div>

            <div class="column">
                <table class="details-table">
                    <tr>
                        <td class="label"><span></span></td>
                        <td class="detail"></td>
                    </tr>
                    <tr>
                        <td class="label"><span></span></td>
                        <td class="detail"></td>
                    </tr>
                    <tr>
                        <td class="label"><span></span></td>
                        <td class="detail"></td>
                    </tr>
                    <tr>
                        <td class="label"><span></span></td>
                        <td class="detail"></td>
                    </tr>
                    <tr>
                        <td class="label"><span></span></td>
                        <td class="detail"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="section-title">Request Details</div>
        <div class="two-columns">
            <div class="column">
                <table class="details-table">
                    <tr>
                        <td class="label"><span>RPE #:</span></td>
                        <td class="detail">{{ $product_evaluations->RpeNumber }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Manual #:</span></td>
                        <td class="detail">{{ $product_evaluations->ManualRpeNumber }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Date Requested:</span></td>
                        <td class="detail">
                            @if($product_evaluations->CreatedDate != null)
                                {{ date('Y-m-d', strtotime($product_evaluations->CreatedDate)) }}
                            @else
                                {{ date('Y-m-d ', strtotime($product_evaluations->created_at)) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><span>Date Required:</span></td>
                        <td class="detail">{{ $product_evaluations->DueDate }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Priority:</span></td>
                        <td class="detail">
                             @if($product_evaluations->Priority == 1)
                                IC Application
                            @elseif($product_evaluations->Priority == 3)
                                Second Priority
                            @elseif($product_evaluations->Priority == 5)
                                First Priority
                            @else
                                {{ $product_evaluations->Priority }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><span>&nbsp;</span></td>
                        <td class="detail">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="label"><span>&nbsp;</span></td>
                        <td class="detail">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Project Name:</span></td>
                        <td class="detail">{{ optional($product_evaluations->projectName)->Name  }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Application:</span></td>
                        <td class="detail">{{ optional($product_evaluations->product_application)->Name  }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Potential Volume:</span></td>
                        <td class="detail">{{ $product_evaluations->PotentialVolume }} </td>
                    </tr>
                    <tr>
                        <td class="label"><span>Target Raw Price:</span></td>
                        <td class="detail">{{ $product_evaluations->PotentiaTargetRawPricelVolume }} </td>
                    </tr>
                </table>
            </div>

            <div class="column">
                <table class="details-table">
                    <tr>
                        <td class="label"><span>Primary Sales Person:</span></td>
                        <td class="detail">
                            @if($product_evaluations->primarySalesPerson)
                                {{ optional($product_evaluations->primarySalesPerson)->full_name}}
                            @elseif($product_evaluations->primarySalesPersonById)
                                {{ optional($product_evaluations->primarySalesPersonById)->full_name}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><span>Secondary Sales Person:</span></td>
                        <td class="detail">
                            @if($product_evaluations->secondarySalesPerson)
                                {{ optional($product_evaluations->secondarySalesPerson)->full_name}}
                            @elseif($product_evaluations->secondarySalesPersonById)
                                {{ optional($product_evaluations->secondarySalesPersonById)->full_name}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><span>&nbsp;</span></td>
                        <td class="detail">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Attention To:</span></td>
                        <td class="detail">
                            @if($product_evaluations->AttentionTo == 1)
                                RND
                            @elseif($product_evaluations->AttentionTo == 2)
                                QCD
                            @else
                                {{ $product_evaluations->AttentionTo }}
                            @endif
                        </td>
                    </tr>
                     <tr>
                        <td class="label"><span>&nbsp;</span></td>
                        <td class="detail">&nbsp;</td>
                    </tr>
                     <tr>
                        <td class="label"><span>Sample Name</span></td>
                        <td class="detail">{{ $product_evaluations->SampleName  }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Manufacturer</span></td>
                        <td class="detail">{{ $product_evaluations->Manufacturer  }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Supplier</span></td>
                        <td class="detail">{{ $product_evaluations->Supplier  }}</td>
                    </tr>
                </table>
            </div>
        </div>
         <div>
            <table class="details-table" style="width: 100%;">
                <tr>
                    <td class="label" style="width: 25%; vertical-align: top;">
                        <span>Objective for RPE Proj.:</span>
                    </td>
                    <td class="detail" style="width: 75%; vertical-align: top;">
                        {!! nl2br(e($product_evaluations->ObjectiveForRpeProject)) !!}
                    </td>
                </tr>
            </table>

        </div>
        <div class="section-title">Evaluation Details</div>
        <div class="two-columns">
            <div class="column">
                <table class="details-table">
                    <tr>
                        <td class="label"><span>DDW Number:</span></td>
                        <td class="detail">{{ optional($product_evaluations)->DdwNumber }}</td>
                    </tr>
                    <tr>
                        @php
                                    $rpeResult = $product_evaluations->RpeResult;
                                    $pattern = '/\[(.*?)\]/';
                                
                                    $rpeResultLinked = preg_replace_callback($pattern, function($matches) {
                                        $code = $matches[1];
                                        $product = getProductIdByCode($code);
                                        
                                        if ($product == null)
                                        {
                                            return $matches[0];
                                        }

                                        if (auth()->user()->role->type == 'LS' || auth()->user()->role->type == 'IS')
                                        {
                                            if ($product->status == 4)
                                            {
                                                return '<a href="'.url('view_product/'.$product->id).'">'.$matches[0].'</a>';
                                            }
                                        }
                                        else
                                        {
                                            if ($product->status == 4)
                                            {
                                                return '<a href="'.url('view_product/'.$product->id).'">'.$matches[0].'</a>';
                                            }
                                            if ($product->status == 2)
                                            {
                                                return '<a href="'.url('view_new_product/'.$product->id).'">'.$matches[0].'</a>';
                                            }
                                            if ($product->status == 1)
                                            {
                                                return '<a href="'.url('view_draft_product/'.$product->id).'">'.$matches[0].'</a>';
                                            }
                                            if ($product->status == 5)
                                            {
                                                return '<a href="'.url('view_archive_products/'.$product->id).'">'.$matches[0].'</a>';
                                            }
                                        }
                                        return $matches[0];
                                    }, $rpeResult);
                                @endphp
                        <td class="label"><span>RPE Recommendation:</span></td>
                        <td class="detail">{!! nl2br($rpeResultLinked) !!}</td>
                    </tr>
                </table>
            </div>

            <div class="column">
                <table class="details-table">
                    <tr>
                        <td class="label"><span>Date Received:</span></td>
                        <td class="detail">{{ $product_evaluations->DateReceived ? date('M d, Y', strtotime($product_evaluations->DateReceived)) : 'NA' }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Date Started:</span></td>
                        <td class="detail">{{ $product_evaluations->DateStarted ? date('M d, Y', strtotime($product_evaluations->DateStarted)) : 'NA' }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Date Completed:</span></td>
                        <td class="detail">{{ $product_evaluations->DateCompleted ? date('M d, Y', strtotime($product_evaluations->DateCompleted)) : 'NA' }}</td>
                    </tr>
                    <tr>
                        <td class="label"><span></span></td>
                        <td class="detail"></td>
                    </tr>
                     @php
                                    $dateReceived = $product_evaluations->DateReceived ? strtotime($product_evaluations->DateReceived) : null;
                                    $dueDate = $product_evaluations->DueDate ? strtotime($product_evaluations->DueDate) : null;
                                    $dateCompleted = $product_evaluations->DateCompleted ? strtotime($product_evaluations->DateCompleted) : null;
                                    $leadReceived = $product_evaluations->DateReceived;
                                    $leadDueDate = $product_evaluations->DueDate;

                                    if ($leadReceived && $leadDueDate) {
                                        $start = DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime($leadReceived)));
                                        $end = DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime($leadDueDate)));

                                        $leadtime = 0;

                                        while ($start <= $end) {
                                            $dayOfWeek = $start->format('N'); 
                                            if ($dayOfWeek < 6) { 
                                                $leadtime++;
                                            }
                                            $start->modify('+1 day');
                                        }
                                    } else {
                                        $leadtime = 'NA';
                                    }

                                    if (!$dateCompleted) {
                                        $dateCompleted = time();
                                    }

                                    if ($dueDate) {
                                        $delay = ($dateCompleted - $dueDate) / (60 * 60 * 24); 
                                        $delayed = number_format($delay, 0);
                                    } else {
                                        $delayed = 'NA';
                                    }
                                @endphp
                    <tr>
                        <td class="label"><span>Lead Time:</span></td>
                        <td class="detail">{{ $leadtime}}</td>
                    </tr>
                    <tr>
                        <td class="label"><span>Delayed:</span></td>
                        <td class="detail">{{ $delayed}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    {{-- <div class="signatures">
        <div class="signature-space">[Signature over printed name]</div>
        <div class="signature-space">[Signature over printed name]</div>
        <div class="signature-space">[Signature over printed name]</div>
    </div> --}}
</body>
</html>