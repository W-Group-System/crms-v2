<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Product Data Sheet</title>

    <style>
        @page 
        {
            margin: 0;
        }

        body 
        {
            /* font-family: Arial, sans-serif; */
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;

            background-image: url("{{asset('images/pbi_template.jpg')}}");
            background-position: center;
            background-repeat: no-repeat;
            background-size:cover;
            z-index: -1;
        }
        .page-break 
        {
            page-break-after: always;
        }
        /* .container 
        {
            width: 100%;
            border-collapse: collapse;
        }
        .left-section, .right-section 
        {
            vertical-align: top;
        }
        .left-section
        {
            width: 30%;
            padding-right: 10px;
        }
        .right-section 
        {
            width: 50%;
        }
        h2 
        {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .section 
        {
            margin-bottom: 10px;
        }
        .page-break 
        {
            page-break-after: always;
        } */
    </style>
</head>
<body>
    <div class="page-break" style="margin: 70px;">
        <h2 style="text-align: center; font-size: 28px; margin-top: 15%; margin-bottom: 0;">{{$pds->products->code}}</h2>
        <p style="text-align: center; margin: 0;"><b>Product Data Sheet</b></p>
        <p style="text-align: center; margin: 0;"><b>Control Code. {{$pds->ControlCode}}</b></p>

        <h3 style="margin: 0">DESCRIPTION</h3>
        <hr>
        <p style="text-align: justify;">{{$pds->Description}}</p>

        <h3 style="margin: 0">APPLICATION</h3>
        <hr>
        <p style="text-align: justify;">{{$pds->Description}}</p>

        <h3 style="margin: 0">POTENTIAL BENEFIT</h3>
        <hr>
        <p style="text-align: justify;">
            @foreach ($pds->productPotentialBenefit as $key=>$pb)
                {{$key+1}}. {{$pb->Benefit}} <br>
            @endforeach
        </p>

        <h3 style="margin: 0">DIRECTION FOR USE</h3>
        <hr>
        <p style="text-align: justify;">
            {{$pds->DirectionForUse}}
        </p>
        
        <h3 style="margin: 0">PHYSICO-CHEMICAL ANALYSES</h3>
        <hr>
        <table width="100%" style="margin-bottom: 16px;">
            @foreach ($pds->productPhysicoChemicalAnalyses as $pca)
                <tr>
                    <td>{{$pca->Parameter}}</td>
                    <td>{{$pca->Value}}</td>
                    <td style="margin-left: 10px;">{{$pca->Remarks}}</td>
                </tr>
            @endforeach
        </table>

        <h3 style="margin: 0">HEAVY METALS</h3>
        <hr>
        <table width="100%">
            @foreach ($pds->productHeavyMetal as $pca)
                <tr>
                    <td>{{$pca->Parameter}}</td>
                    <td>{{$pca->Value}}</td>
                    {{-- <td>{{$pca->Remarks}}</td> --}}
                </tr>
            @endforeach
        </table>
    </div>

    <div class="page-break" style="margin: 70px; margin-top: 130px;">
        <table width="50%" style="float:left;">
            <h3 style="margin: 0">NUTRIONAL INFORMATION</h3>
            <hr>
            <tr>
                <td>
                    @foreach ($pds->productNutritionalInformation as $pca)
                        <tr>
                            <td>{{$pca->Parameter}}</td>
                            <td>{{$pca->Value}}</td>
                        </tr>
                    @endforeach
                </td>
            </tr>
        </table>
        <table width="50%" style="float:left;">
            <tr>
                <td>
                    <h3 style="margin: 0">HEAVY METALS</h3>
                    <hr>
                    @foreach ($pds->productNutritionalInformation as $pca)
                        <tr>
                            <td>{{$pca->Parameter}}</td>
                            <td>{{$pca->Value}}</td>
                        </tr>
                    @endforeach
                </td>
            </tr>
        </table>

        <h3 style="margin: 0">TECHNICAL ASSISTANCE</h3>
        <hr>
        <p style="text-align: justify;">
            {{$pds->TechnicalAssistance}}
        </p>

        <h3 style="margin: 0">PURITY AND LEGAL STATUS</h3>
        <hr>
        <p style="text-align: justify;">
            {{$pds->PurityAndLegalStatus}}
        </p>

        <h3 style="margin: 0">PACKAGING</h3>
        <hr>
        <p style="text-align: justify;">
            {{$pds->Packaging}}
        </p>

        <h3 style="margin: 0">STORAGE</h3>
        <hr>
        <p style="text-align: justify;">
            {{$pds->Storage}}
        </p>

        <h3 style="margin: 0">CERTIFICATIONS</h3>
        <hr>
        <p style="text-align: justify;">
            {{$pds->Certification}}
        </p>

        <i style="text-align: justify;">
            It is advisable for the end user to perform own test to determine suitability of the product for their intended use and processing conditions. Technical data contained herein are given only as a guide and may be different from customer’s results due to different testing methods, and should not be considered as a warranty of any kind.
        </i>
    </div>
</body>
</html>
