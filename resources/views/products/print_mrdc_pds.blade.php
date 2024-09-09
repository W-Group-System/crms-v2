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
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;

            background-image: url("{{asset('images/mrdc_template.jpg')}}");
            background-position: center;
            background-repeat: no-repeat;
            background-size:contain;
            z-index: -1;
        }
        .container 
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
            /* border-right: 1px solid black; */
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
        }
    </style>
</head>
<body>
    <div class="page-break" style="margin: 70px;">
        <h2 style="text-align: center; font-size: 28px; margin-top: 130px;">{{$pds->products->code}}</h2>
        <p style="text-align: center;">Product Data Sheet</p>

        <table class="container">
            <tr>
                <td class="left-section">
                    <div class="section">
                        <h2>PRODUCT DESCRIPTION</h2>
                        <p style="text-align: justify">{!! nl2br($pds->Description) !!}</p>
                    </div>
    
                    <div class="section">
                        <h2>COMPOSITION AND LEGAL STATUS</h2>
                        <p style="text-align: justify">{{$pds->PurityAndLegalStatus}}</p>
                    </div>
    
                    <div class="section">
                        <h2>DIRECTION FOR USE</h2>
                        <p>{{$pds->DirectionForUse}}</p>
                    </div>
    
                    <div class="section">
                        <h2>PACKAGING</h2>
                        <p style="text-align: justify">{{$pds->Packaging}}</p>
                    </div>
    
                    <div class="section">
                        <h2>SHELF LIFE & STORAGE</h2>
                        <p style="text-align: justify">{{$pds->Storage}}</p>
                    </div>
                </td>
                
                <td class="right-section">
                    <div class="section">
                        <h2>PHYSICOCHEMICAL ANALYSES</h2>
                        <hr>
                        <div class="container">
                            <table style="margin-top: 1px;" class="table-bordered table">
                                @foreach ($pds->productPhysicoChemicalAnalyses as $pca)
                                    <tr>
                                        <td>
                                            <p style="margin: 0px; width: 200px; word-break: break-all;">
                                                <strong>{{ $pca->Parameter }}</strong>
                                                <p style="margin: 0px;">{{$pca->Remarks}}</p>
                                            </p>
                                        </td>
                                        <td>
                                            <p style="margin: 0px;">{{$pca->Value}}</p>
                                        </td>
                                    </tr> 
                                @endforeach
                            </table>
                        </div>
                    </div>
    
                    <div class="section">
                        <h2>MICROBIOLOGICAL ANALYSES</h2>
                        <hr>
                        <table width="100%">
                            @foreach ($pds->productMicrobiologicalAnalysis as $pm)
                                <tr>
                                    <td>
                                        <p style="margin: 0px;">{{$pm->Parameter}}</p>
                                    </td>
                                    <td>
                                        <p style="margin: 0px;">{{$pm->Value}}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
    
                    <div class="section">
                        <h2>HEAVY METALS</h2>
                        <hr>
                        <table width="100%">
                            @foreach ($pds->productHeavyMetal as $heavyMetal)
                                <tr>
                                    <td>
                                        <p style="margin: 0px">{{$heavyMetal->Parameter}}</p>
                                    </td>
                                    <td>
                                        <p style="margin: 0px">{{$heavyMetal->Value}}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    


</body>
</html>
