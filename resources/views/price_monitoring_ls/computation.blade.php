<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computation</title>
    <style>
        /* @page {
            margin: 0;
        } */

        body {
            font-family: Arial, sans-serif;
            position: relative;
            min-height: 100vh;
        }

        .header-table {
            width: 100%;
            border-spacing: 0;
            background-color:#c0c0c0;
        }
        .header-table th, .header-table td{
            border: 1px solid black;
            text-align: center;
            font-size: 12px;
            padding: 0;
            margin: 0;
        }
        .header-table p, .header-table h2{
            text-align: center;
            padding: 1px;
            margin: 1px;
        }
        .customer-table {
            margin-top: 10px;
            width: 100%;
            border-spacing: 0;
        }
        .customer-table th, .customer-table td{
            border: 1px solid black;
            text-align: center;
            font-size: 12px;
        }
        .customer-table p{
            padding: 1px;
            margin: 1px;
        }
        .customer-table .label{
            text-align: left;
            background-color:#c0c0c0;
            width: 20%;
        }
        .customer-table .data{
            text-align: left;
        }
        .customer-table .date{
            text-align: center;
            width: 20%;
        }
        .page-break {
            page-break-after: always;
        }

        /* dosfds */
        .computation-table {
            margin-top: 10px;
            margin-left: 30px;
            width: 70%;
            border-collapse: collapse;
            font-size: 10px;
        }
        
        .computation-table td, .computation-table th {
            padding: 2px;
        }

        .no-border {
            border: none;
            width: 20%;
        }

        .heading {
            text-align: left;
            font-weight: bold;
        }

        .sub-heading {
            font-weight: normal;
            width: 50%
        }

        .bold-line {
            border-bottom: 1px solid black;
        }

        .bold-line-top {
            padding-top:10px;
            border-top: 1px solid black;
        }

        .total-row {
            border-top: 2px solid black;
            font-weight: bold;
        }

        .note {
            font-style: italic;
        }
        .computation-div{
            margin-top: 10px;
            border: 1px solid black;
            padding: 10 20px;
        }
        .computation-div b{
            font-size: 10px;
        }
        .computation-table .values{
            background-color: #eaeaea; 
            padding: 1px; 
            border: 1px solid #ccc; 
            width: 100px; 
            text-align: right;
            margin: 1px
        }
        .values-div{

            border: 1px solid black;
            border-top:none;
            padding: 10 20px;
            font-size: 10px;
        }
        .price-table {
            margin-top: 10px;
            /* margin-left: 30px; */
            width: 77%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .price-table .values{
            background-color: #eaeaea; 
            padding: 1px; 
            border: 1px solid #ccc; 
            width: 100px; 
            text-align: right;
            margin: 1px
        }
        .signature-div {
            margin-top: 10px;
            border: 1px solid black;
            text-align: center;
            font-size: 10px;
        }
        .signature-div .signatures {
            display: flex;
            text-align: center;
        }

        .signature-div .signature-space {
            display: inline-block;
            text-align: center;
            margin: 70px 15px 5px 15px;
        }
    </style>
</head>
<body>
   @foreach ($price_monitoring_ls->requestPriceProducts as $item)
    <table class="header-table">
        <tr>
            <td>
                <p>Marine Resources Development Corporation</p>
            </td>
        </tr>
        <tr>
            <td>
                <h2>PRODUCT COSTING TEMPLATE</h2>
            </td>
        </tr>
    </table>  
    <table class="customer-table">
        <tr>
            <td class="label">
                <p>Customer</p>
            </td>
            <td class="data" colspan="2">
                <p>{{ optional($price_monitoring_ls->client)->Name }}</p>
            </td>
            <td class="date">
                <p>{{ \Carbon\Carbon::parse($price_monitoring_ls->DateRequested)->format('F j, Y') }}</p>
            </td>
        </tr>
        <tr>
            <td class="label">
                <p>Product Name</p>
            </td>
            <td class="data" colspan="3">
                <p>{{ optional($item->products)->code }}</p>
            </td>
        </tr>
        <tr>
            <td class="label">
                <p>Classification</p>
            </td>
            <td class="data">
                <p>@if ($item->Type == 1)
                    Pure
                @elseif ($item->Type == 2)
                    Blend
                @else
                {{ $item->Type }}
                @endif</p>
            </td>
            <td class="label">
                <p>Category</p>
            </td>
            <td class="data">
                <p>{{ optional($item->product_application)->Name }}</p>
            </td>
        </tr>
    </table>  
    <div class="computation-div">
        <div>
            <b>A. Product Cost per Unit (kg)</b>
        </div>
        <table class="computation-table">
            <tr>
                <th class="heading">1. Manufacturing Cost</th>
            </tr>
            <tr>
                <td class="sub-heading no-border">a. Raw Materials</td>
                <td class="no-border"></td>
                <td><div class="values">{{ $item->ProductRmc }}</div></td>
            </tr>
            <tr>
                <td class="sub-heading no-border">b. Direct Labor</td>
                <td class="no-border"></td>
                <td><div class="values">{{ $item->LsalesDirectLabor }}</div></td>
            </tr>
            <tr>
                <td class="sub-heading no-border">c. Factory Overhead</td>
                <td class="no-border"></td>
                <td><div class="values">{{ $item->LsalesFactoryOverhead }}</div></td>
            </tr>
            <tr>
                <td class="bold-line">Total Manufacturing Cost per Unit (kg)</td>
                <td class="no-border"></td>
                <td class="bold-line"><div class="values">{{ number_format($item->ProductRmc + $item->LsalesDirectLabor + $item->LsalesFactoryOverhead, 2) }}</div></td>
            </tr>
            <tr style="margin-top: 20px">
                <td class="bold-line">Blending Loss (1% of Raw Materials)</td>
                <td class="no-border"></td>
                <td class="bold-line"><div class="values">{{ $item->LsalesBlendingLoss }}</div></td>
            </tr>
            <tr>
                <th colspan="3" class="heading">2. Operating Cost</th>
            </tr>
            <tr>
                <td class="sub-heading no-border">d. Delivery Cost</td>
                <td class="no-border"></td>
                <td><div class="values">{{ $item->LsalesDeliveryCost }}</div></td>
            </tr>
            <tr>
                <td class="sub-heading no-border">e. Financing Cost (5% of Mfg. Cost)</td>
                <td class="no-border"></td>
                <td><div class="values">{{ $item->LsalesFinancingCost }}</div></td>
            </tr>
            <tr>
                <td class="sub-heading no-border">f. General and Administration Expense</td>
                <td class="no-border">{{ $item->gaeType->ExpenseName }}</td>
                <td><div class="values">{{ $item->LsalesGaeValue }}</div></td>
            </tr>
            <tr>
                <td class="sub-heading no-border">g. Other Cost Requirements</td>
                <td class="no-border"></td>
                <td><div class="values">{{ $item->OtherCostRequirements }}</div></td>
            </tr>
            <tr>
                <td class="bold-line">Total Operating Cost per Unit (kg)</td>
                <td class="no-border"></td>
                <td class="bold-line"><div class="values">{{ number_format($item->LsalesDeliveryCost + $item->LsalesFinancingCost + $item->LsalesGaeValue + $item->OtherCostRequirements, 2) }}</div></td>
            </tr>
            @php
                $totalCost = $item->ProductRmc +
                                        $item->LsalesDirectLabor +
                                        $item->LsalesFactoryOverhead +
                                        $item->LsalesDeliveryCost +
                                        $item->LsalesFinancingCost +
                                        $item->LsalesGaeValue +
                                        $item->OtherCostRequirements +
                                        $item->LsalesBlendingLoss;

                            $markupPercent = $item->LsalesMarkupPercent;
                            $markupValue = $item->LsalesMarkupValue;

                            $sellingPrice = $totalCost + $markupValue;
                            $sellingPriceWithVAT = $sellingPrice * 0.12;
                            $sumWithVat = $sellingPrice + $sellingPriceWithVAT;

                            $formattedSellingPrice = number_format($sellingPrice, 2);
                            $formattedSellingPriceWithVAT = number_format($sellingPriceWithVAT, 2);
                            $formattedSumWithVat = number_format($sumWithVat, 2);
            @endphp
            <tr>
                <td class="heading bold-line">Total Product Cost per Unit (kg)</td>
                <td class="no-border"></td>
                <td class="bold-line"><div class="values">{{ number_format($totalCost, 2) }}</div></td>
            </tr>
        </table>
    </div>
    <div class="values-div">
        <div>
            <b>B. Markup</b>
        </div>
        <table class="computation-table">
            <tr>
                <td class="sub-heading"></td>
                <td class="no-border"></td>
                <td><div class="values">{{ $item->LsalesMarkupPercent }}%</div></td>
            </tr>
            <tr>
                <td class="sub-heading"></td>
                <td class="no-border"></td>
                <td><div class="values">Php {{ $item->LsalesMarkupValue }}</div></td>
            </tr>
        </table>
    </div>
    <div class="values-div">
        <table class="price-table">
            <tr>
                <td class="sub-heading"> <b>C. Selling Price (VAT Exclusive)</b></td>
                <td class="no-border"></td>
                <td><div class="values">{{$formattedSellingPrice }}</div></td>
            </tr>
        </table>
    </div>
    <div class="values-div">
        <table class="price-table">
            <tr>
                <td class="sub-heading"> <b>D. Selling Price (12% VAT Inclusive)</b></td>
                <td class="no-border"></td>
                <td><div class="values">{{$formattedSumWithVat }}</div></td>
            </tr>
        </table>
    </div>
    <div class="values-div">
        <div>
            <b>Recommendation</b>
        </div>
        <table class="price-table" style="padding: 20px">
            
        </table>
    </div>
    <div class="computation-div">
        <div>
            <b>Notes</b>
        </div>
        <table class="price-table" style="padding: 20px">
            
        </table>
    </div>
    <div class="signature-div">
        <div class="signatures">
            <div class="signature-space bold-line-top">[signature over printed name]</div>
            <div class="signature-space bold-line-top">[signature over printed name]</div>
            <div class="signature-space bold-line-top">[signature over printed name]</div>
            <div class="signature-space bold-line-top">[signature over printed name]</div>
        </div>
    </div>
    @if (!$loop->last)
    <div class="page-break"></div>
    @endif
   @endforeach
</body>
</html>
