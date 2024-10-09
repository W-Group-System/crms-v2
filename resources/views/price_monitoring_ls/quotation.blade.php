<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            padding: 0;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top; 
        }

        .left-column {
            width: 30%;
            padding: 10px 10px 10px 50px;
        }

        .right-column {
            width: 70%;
            padding: 20px;
        }
        .table1, .table1 th, .table1 td{
            border: 1px solid black;
            text-align: center;
            font-size: 12px;
            padding: 15px
        }
        .left-div {
            text-align: right;
        }
        .right-p {
            font-size: 12px;
        }
        .label {
            font-size: 12px;
            font-weight: bold;
        }
        p {
            text-align: justify;
            text-justify: inter-word;
        }
        .detail {
            font-size: 12px;
            padding-left: 10px;
        }
        .details-table{
            width: 80%
        }
        .signature-section {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            border-top: 1px solid #ccc;
        }

        .signature-fields {
            display: block; 
            margin: 20px 0;
        }

        .signature-fields .field {
            display: inline-block;
            margin: 0 10px;
        }

        .signature-fields .field input {
            width: 100px;
            padding: 15px 50px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .contact-info {
            text-align: left;
            font-size: 12px;
        }

        .contact-info a {
            color: #000;
            text-decoration: none;
        }

        .contact-info a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td class="left-column">
                <div style="text-align: center; margin-bottom: 10px;">
                    <img src="images/mrdc-logo.png" alt="Description of Image" style="max-width: 85%; height: auto;">
                </div>
                <div class="left-div">
                    <b style="color: green; font-size:15px">
                        MARINE<br>
                        RESOURCES<br>
                        DEVELOPMENT<br>
                        CORPORATION
                    </b>
                </div>       
                <div class="left-div" style="margin-top: 15;">
                    <b style="font-size:9;">
                        26th Floor, W Building,
                        Fifth Avenue, BGC, 1634,
                        Taguig City, Philippines
                    </b>
                </div>     
                <div class="left-div" style="margin-top: 10;">
                    <p style="font-size:7;">T: +632.856.3838 F: +632.856.1033</p>
                </div>    
                <div class="left-div">
                    <p style="font-size:8; text-align:right;">info@mrdccarageenan.com
                        www.mrdccarageenan.com</p>
                </div>   
                <div class="left-div">
                    <b style="font-size:10;">
                        WE NEVER STOP
                        INNOVATING
                    </b>
                </div>          
            </td>            
            <td class="right-column">
                <p style="font-size: 9;">{{ \Carbon\Carbon::parse($price_monitoring_ls->DateRequested)->format('F j, Y') }}</p>
                <p style="margin-bottom: 0; font-size:9; font-weight:bold;">{{optional($price_monitoring_ls->clientContact)->ContactName}}</p>
                <p style="font-size: 9; font-weight:bold; margin-bottom:0; line-height:-10px;">{{ optional($price_monitoring_ls->client)->Name }}</p>
                <p style="line-height:0.5; font-size:8">{{ optional($price_monitoring_ls->clientCompanyAddress)->Address }}</p>
                <br>
                <p style="font-size: 9;">Dear, {{optional($price_monitoring_ls->clientContact)->ContactName}}</p>
                <p class="right-p">Greetings!</p>
                <p class="right-p">We are pleased to submit for your consideration our price offer for the following product/s indicated below:</p>
                @if ($price_monitoring_ls->TaxType == "20")
                <table class="table1">
                    <thead>
                        <tr style="gray">
                            <th>Product</th>
                            <th>PRICE/Kg VAT Ex</th>
                            <th>Quantity (Kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($price_monitoring_ls->requestPriceProducts as  $prcieProduct )
                        <tr>
                         @php
                             $totalCost = $prcieProduct->ProductRmc +
                             $prcieProduct->LsalesDirectLabor +
                             $prcieProduct->LsalesFactoryOverhead +
                             $prcieProduct->LsalesDeliveryCost +
                             $prcieProduct->LsalesFinancingCost +
                             $prcieProduct->LsalesGaeValue +
                             $prcieProduct->OtherCostRequirements +
                             $prcieProduct->LsalesBlendingLoss;
 
                             
                            $markupPercent = $prcieProduct->LsalesMarkupPercent;
                            $markupValue = $prcieProduct->LsalesMarkupValue;

                            $sellingPrice = $totalCost + $markupValue;
                            $sellingPriceWithVAT = $sellingPrice * 0.12;
                            $sumWithVat = $sellingPrice + $sellingPriceWithVAT;

                            $formattedSellingPrice = number_format($sellingPrice, 2);
                            $formattedSellingPriceWithVAT = number_format($sellingPriceWithVAT, 2);
                            $formattedSumWithVat = number_format($sumWithVat, 2);
                         @endphp
                             <td>{{ optional($prcieProduct->products)->code }}</td>
                             <td>{{ $formattedSellingPrice }}</td>
                             <td>{{ $prcieProduct->QuantityRequired }}</td>
                         </tr>
                        @endforeach
                     </tbody>
                </table>
                @elseif ($price_monitoring_ls->TaxType == "10")
                <table class="table1">
                    <thead>
                        <tr style="background-color:gray">
                            <th>Product</th>
                            <th>PRICE/Kg VAT In</th>
                            <th>Quantity (Kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($price_monitoring_ls->requestPriceProducts as  $prcieProduct )
                       <tr>
                        @php
                            $totalCost = $prcieProduct->ProductRmc +
                            $prcieProduct->LsalesDirectLabor +
                            $prcieProduct->LsalesFactoryOverhead +
                            $prcieProduct->LsalesDeliveryCost +
                            $prcieProduct->LsalesFinancingCost +
                            $prcieProduct->LsalesGaeValue +
                            $prcieProduct->OtherCostRequirements +
                            $prcieProduct->LsalesBlendingLoss;

                            $totalCost = round($totalCost, 2);

                            $markupPercent = $prcieProduct->LsalesMarkupPercent;
                            $markupValue = $prcieProduct->LsalesMarkupValue;

                            $markupValue = (float) $markupValue;

                            $sellingPrice = $totalCost + $markupValue;
                            $sellingPriceWithVAT = $sellingPrice * 0.12;
                            $sumWithVat = $sellingPrice + $sellingPriceWithVAT;

                            $formattedSellingPrice = number_format($sellingPrice, 2);
                            $formattedSellingPriceWithVAT = number_format($sellingPriceWithVAT, 2);
                            $formattedSumWithVat = number_format($sumWithVat, 2);
                        @endphp
                            <td>{{ optional($prcieProduct->products)->code }}</td>
                            <td>{{ $formattedSumWithVat }}</td>
                            <td>{{ $prcieProduct->QuantityRequired }}</td>
                        </tr>
                       @endforeach
                    </tbody>
                </table>
                @endif
                <br>
                <table class="details-table" style="margin-top:-10;">
                    <tr>
                        <td class="label">LEAD TIME(if available):</td>
                        <td class="detail">approximately 15 working days</td>
                    </tr>
                    <tr>
                        <td class="label">LEAD TIME(if not available):</td>
                        <td class="detail">approximately 2-4 months</td>
                    </tr>
                    <tr>
                        <td class="label">TERMS OF PAYMENT:</td>
                        <td class="detail">{{ $price_monitoring_ls->paymentterms->Name}}</td>
                    </tr>
                    <tr>
                        <td class="label">PACKAGING:</td>
                        <td class="detail">{{ $price_monitoring_ls->PackagingType}}</td>
                    </tr>
                    <tr>
                        <td class="label">VALIDITY:</td>
                        {{-- <td class="detail">{{ \Carbon\Carbon::parse($price_monitoring_ls->ValidityDate)->format('F j, Y') }}</td> --}}
                        <td class="detail">{{date('F j, Y', strtotime($price_monitoring_ls->ValidityDate))}}</td>
                    </tr>
                    <tr>
                        <td class="label">DELIVERY SCHEDULE</td>
                        {{-- <td class="detail">{{ \Carbon\Carbon::parse($price_monitoring_ls->PriceLockPeriod)->format('F j, Y') }}</td> --}}
                        <td class="detail">{{$price_monitoring_ls->PriceLockPeriod}}</td>
                    </tr>
                </table>
                <p class="right-p">NOTE: Above price may change without prior notice</p>
                <p class="right-p">We are looking forward to your favorable feedback.</p>
                <p class="right-p">Thank you for your time and cosideration!</p>
                <p class="right-p">Sincerely,</p>
                @foreach ($price_monitoring_ls->requestPriceProducts as $price_products)
                    @if($price_products->LsalesMarkupPercent >= 15)
                        <p class="right-p">{{auth()->user()->full_name}}</p>
                    @else
                        <p class="right-p">Maricris J. Ribon</p>
                    @endif
                @endforeach
                <br>
                <br>
                {{-- {{dd($price_monitoring_ls->requestPriceProducts)}} --}}
                @foreach ($price_monitoring_ls->requestPriceProducts as $price_products)
                    @if($price_products->LsalesMarkupPercent >= 15)
                    <b style="color: green; font-size:14px">
                        Marine Resources Development Corporation
                    </b> 
                    <p  class="right-p">---This is a computer-generated and no signature is required---</p>
                    @else

                    <table cellpadding="0" border="0" cellspacing="0" style="width:100%;">
                        <tr>
                            <td>
                                <p style="font-size:9;">Noted by</p>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <p style="font-size:8; text-align:center; border-top:1px solid black; margin: 35 10 0 10;">[signature over printed name]</p>
                            </td>
                            <td>
                                <p style="font-size:8; text-align:center; border-top:1px solid black; margin: 35 10 0 10;">[signature over printed name]</p>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <p style="font-size:9; margin-top:20;">Approved by</p>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <p style="font-size:8; text-align:center; border-top:1px solid black; margin: 35 10 0 10;">[signature over printed name]</p>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                    @endif
                @endforeach
            </td>
        </tr>
    </table>
    <div class="signature-section">
        <p class="right-p">Please confirm your acceptance of this quotation by signing this document</p>
        
        <div class="signature-fields">
            <div class="field">
                <input type="text" placeholder="Sign Here" />
            </div>
            <div class="field">
                <input type="text" placeholder="Print Name & Title" />
            </div>
            <div class="field">
                <input type="text" placeholder="Date" />
            </div>
        </div>
    
        <p class="contact-info">
            If you have any questions about this price quote, please contact MRDC at the following email address: 
            <a href="mailto:info@mrdccarrageenan.com">info@mrdccarrageenan.com</a>.<br>
            Or call us at +632.856.3838.
        </p>
    </div>
    
</body>
</html>
