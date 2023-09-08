<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <!-- Include Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS for Invoice -->
    <style>
        /* Add your custom styles here */
        .invoice {
            /* border: 1px solid #ccc; */
            padding: 20px;
            margin: 20px;
            background-color: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .invoice-no {
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        .next-line {
            margin-bottom: 31px;
        }

        .head_cl {
            padding: 15px;
            background-color: #00aff0;
        }
        .margin {
    margin-bottom: 0!important;
}
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <!-- <h1 class="mt-4">Invoice</h1> -->
            <img src="file:///C:/xampp/htdocs/aryuinvoiceheader.png" alt="Header Image" class="img-fluid">
        </div>

        <!-- Invoice Body -->
        <div class="invoice">
            <div class="row">
                <div class="col-md-12">
                    <p class="invoice-no">#AY04032301</p>
                    <p class="invoice-no">04 March, 2023</p>
                </div>
            </div>
            <div class="next-line"></div>
            <div class="row">
                <div class="col-md-6">
                    <p class="invoice-no">From: ARYU ENTERPRISES PRIVATE LIMITED</p>
                    <p>No. 2/9,First Floor, Murugesan Street,<br>Balavinayagar Nagar,Arumbakkam,<br>Chennai,Tamil Nadu 600106. India</p>
                    <p class="invoice-no">Mobile: 9994715106</p>
                    <p class="invoice-no">Email: <a href="">Yuvaraj@aryuenterprises.com</a></p>
                </div>

                <!-- Right Column (Invoice Items) -->
                <div class="col-md-6">
                    <p class="invoice-no">Invoice To: Medics Research</p>
                    <p>No.13, First floor, Shivan Kovil North Car Street,<br>Near Dr.Sundararajan SMS Hospital,<br>Palayamkottai Market, Tirunelveli – 627002,Tamilnadu, India.</p>
                    <p class="invoice-no">Mobile: +91 86820 79699</p>
                    <p class="invoice-no">Email: <a href="">support@medicsresearch.com</a></p>
                </div>
            </div>
            <div class="next-line"></div>
            <div class="row">
                <div class="col-md-11">
                    <table class="table table-bordered" style="border: 2px solid;">
                        <thead>
                            <tr>
                                <td class="invoice-no" style="background-color: #00aff0;">Project Description</td>
                                <td class="invoice-no" style="background-color: #00aff0;">Quantity</td>
                                <td class="invoice-no" style="background-color: #00aff0;">Amount</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 30px;"> March Month Website, Server and
                                    Software Maintenance</td>
                                <td style="padding: 30px;">1</td>
                                <td style="padding: 30px;">INR 5500</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td style="padding: 15px;">
                                <td class="invoice-no" style="padding: 30px;">Total</td>
                                </td>
                                <td style="padding: 30px;">
                                    INR 5500
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="next-line"></div>
            <div class="row">
                <div class="col-md-12">
                    <p class="invoice-no margin">BANK DETAILS:</p>
                    <p class="margin">Payment Mode: Bank Transfer</p>
                    <p class="margin">Bank Name: Indusind Bank Ltd</p>
                    <p class="margin">Account Name: Aryu Enterprises Private Limited</p>
                    <p class="margin">Account Type: Current</p>
                    <p class="margin">Account No: 259994715106</p>
                    <p class="margin">Branch: Velacherry</p>
                    <p class="margin">IFSC Code: INDB00060</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <img src="file:///C:/xampp/htdocs/aryuinvoicefooter.png" alt="Header Image" class="img-fluid">
        </div>
    </div>
    <!-- Include Bootstrap 5 JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>