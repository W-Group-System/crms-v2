<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 30px;
            padding: 0;
            border: 1px solid black;
            font-family: Arial, sans-serif;
            position: relative;
            min-height: 100vh;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .header-table td {
            padding: 5px;
            border-bottom: 2px solid black; 
        }

        .logo {
            width: 50px;
            height: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        table, th, td {
            border: 1px solid black;
            padding: 3px; 
        }

        .table-header {
            text-align: center;
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            text-align: left;
            margin-top: 5px;
        }

        .form-info td {
            padding: 2px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }

        .dispatch-info td {
            border: none;
            padding: 3px; 
        }

        .remarks {
            height: 50px;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td style="width: 70%; text-align: left;">
                    <img src="path/to/logo.png" alt="Company Logo" class="logo"><br>
                    <strong>W HYDROCOLLOIDS, INC.</strong>
                </td>
                <td style="width: 30%; text-align: right;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="text-align: right;"><strong>Sample Request Form</strong></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">FR-S&M-01 rev 04</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>     

        <!-- Sub-header -->
        <div class="sub-header">
            K P Manish Global Ingredients Pvt. Ltd.<br>
            <span style="font-size: 12px;">[ ] Pre-Ship Sample &nbsp;&nbsp; [ ] Regular Sample &nbsp;&nbsp; [ ] Co-Ship Sample</span>
        </div>

        <!-- Form Information -->
        <table class="form-info">
            <tr>
                <td class="label">Request Date:</td>
                <td>22-Aug-2024</td>
                <td class="label">DEADLINE:</td>
                <td>22-Aug-2024</td>
                <td class="label">SRF No.:</td>
                <td>SRF-IS-24-5468</td>
            </tr>
            <tr>
                <td class="label">Attention:</td>
                <td>Aleeth Mehta</td>
                <td class="label">Laboratory:</td>
                <td>RND</td>
                <td class="label">Telephone:</td>
                <td>+91 44 4212 3456</td>
            </tr>
            <tr>
                <td class="label">Address:</td>
                <td colspan="5">41 Raghunayakulu Street, Chennai-600003, INDIA</td>
            </tr>
        </table>

        <!-- Table with Sample Info -->
        <table>
            <thead>
                <tr class="table-header">
                    <th>SRF #</th>
                    <th>IPE #</th>
                    <th>CRF #</th>
                    <th>PRODUCT CODE</th>
                    <th>Label</th>
                    <th>No. of Packs</th>
                    <th>Product Description</th>
                    <th>Remarks (For Internal Purposes)</th>
                    <th>Disposition</th>
                    <th>Disposition Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>SRF-IS-24-5468</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>0.00x 0.00 grams</td>
                    <td>1</td>
                    <td>Sample Product Description</td>
                    <td>No Feedback</td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div class="footer">
            OTHER INSTRUCTIONS: Please send COA and PDS

            <table class="dispatch-info">
                <tr>
                    <td><strong>Date Sent:</strong> -----------------</td>
                    <td><strong>Courier:</strong> -----------------</td>
                    <td><strong>AWB #:</strong> -----------------</td>
                </tr>
            </table>
    
            <!-- Signature Section -->
            <table class="signature-section">
                <tr>
                    <td><strong>Prepared by:</strong> -----------</td>
                    <td><strong>Approved by:</strong> -----------</td>
                    <td><strong>Copy Received by:</strong> -----------</td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
