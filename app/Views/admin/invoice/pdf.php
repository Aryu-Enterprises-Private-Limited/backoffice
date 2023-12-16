<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <!-- Include Bootstrap 5 CSS -->
    <link type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.css" rel="stylesheet">
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

        /* .img-fluid {
            max-width: 40%;
            height: auto;
        } */
        @media print {
            @page {
                size: auto;
                margin: .5rem 0;
            }

            body {
                -webkit-print-color-adjust: exact;
                -moz-print-color-adjust: exact;
                -ms-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">

            <?php $img = file_get_contents(
                'images/aryuinvoiceheader.png'
            );
            $img2 = file_get_contents(
                'images/aryuinvoicefooter.png'
            );
            // Encode the image string data into base64
            $header_img = base64_encode($img);
            $footer_img = base64_encode($img2);
            ?>
            <img src="data:image;base64,<?= $header_img; ?>" style="max-width: 100%;height: auto;">
        </div>
        <!-- Invoice Body -->
        <div class="invoice">
            <div class="row">
                <div class="col-md-12">
                    <p class="invoice-no">#<?= $pdf_opt->invoice_no; ?></p>
                    <!-- <p class="invoice-no">04 March, 2023</p> -->
                    <?php
                    $time_input = strtotime($pdf_opt->invoice_date);
                    $date_input = getDate($time_input);
                    ?>
                    <p class="invoice-no"><?= $date_input['mday'] . ' ' . $date_input['month'] . ', ' . $date_input['year']; ?></p>
                </div>
            </div>
            <div class="next-line"></div>
            <div class="row">
                <div class="col-md-6">
                    <p class="invoice-no">From: <?= $pdf_opt->from_name; ?></p>
                    <p>No. 2/9,First Floor, Murugesan Street,<br>Balavinayagar Nagar,Arumbakkam,<br>Chennai,Tamil Nadu 600106. India</p>
                    <p class="invoice-no">Mobile: 9994715106</p>
                    <p class="invoice-no">Email: <a href="">Yuvaraj@aryuenterprises.com</a></p>
                </div>

                <!-- Right Column (Invoice Items) -->
                <div class="col-md-6">
                    <p class="invoice-no">Invoice To: <?= ucfirst($client_details->first_name) . ' ' . ucfirst($client_details->last_name) . '(' . $client_details->company_name . ')'; ?></p>
                    <p><?= $client_details->address; ?></p>
                    <p class="invoice-no">Mobile: +91 <?= $client_details->phone; ?></p>
                    <p class="invoice-no">Email: <a href=""><?= $client_details->email; ?></a></p>
                </div>
            </div>
            <div class="next-line"></div>
            <div class="row">
                <div class="col-md-11">
                    <table class="table table-bordered" style="border: 2px solid;">
                        <thead>
                            <tr>
                                <td class="invoice-no" style="background-color: #00aff0; padding: 30px; font-weight: bold;margin: 0;padding: 0;">Project Description</td>
                                <td class="invoice-no" style="background-color: #00aff0;padding: 30px; font-weight: bold;margin: 0;padding: 0;">Quantity</td>
                                <td class="invoice-no" style="background-color: #00aff0; padding: 30px; font-weight: bold;margin: 0;padding: 0;">Amount</td>
                                <td class="invoice-no" style="background-color: #00aff0; padding: 30px; font-weight: bold;margin: 0;padding: 0;">Total</td>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $desc = json_decode($pdf_opt->description);
                            $amount = json_decode($pdf_opt->amount);
                            $qnty = json_decode($pdf_opt->quantity);
                            // print_r($amount);die;
                            $combinedArray = [];
                            $add_count = count($desc);
                            $amnt_count = count($amount);
                            $qnty_count = count($qnty);
                            if ($add_count == $amnt_count && $amnt_count == $qnty_count && $qnty_count == $add_count) {
                                for ($i = 0; $i < count($desc); $i++) {
                                    $total = $qnty[$i] * $amount[$i];
                                    $combinedArray[] = [
                                        '0' => $desc[$i],
                                        '1' => $qnty[$i],
                                        '2' => $amount[$i],
                                        '3' => $total
                                    ];
                                }
                                // $grand_totl = array();
                                foreach ($combinedArray as $item) { 
                                    // $grand_totl =+ $item['3'];?>
                                    <tr class="content add">
                                        <td class="text-center"><?= ucfirst($item['0']); ?></td>
                                        <td class="text-center"><?= $item['1'] ?></td>
                                        <td class="text-center"><?= $item['2'] ?></td>
                                        <td class="text-center"><?= $item['3'] ?></td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <td class="text-center">Norecord</td>
                            <?php  } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td style="padding: 15px;">
                                <td style="padding: 15px;">
                                <td class="invoice-no" style="padding: 30px; font-weight: bold;margin: 0;padding: 0;">Grand Total</td>
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
                    <p class="invoice-no" style="font-weight: bold;margin: 0;padding: 0;margin-bottom: 0!important;">BANK DETAILS:</p>
                    <p style="margin-bottom: 0!important;">Payment Mode: Bank Transfer</p>
                    <p style="margin-bottom: 0!important;">Bank Name: Indusind Bank Ltd</p>
                    <p style="margin-bottom: 0!important;">Account Name: Aryu Enterprises Private Limited</p>
                    <p style="margin-bottom: 0!important;">Account Type: Current</p>
                    <p style="margin-bottom: 0!important;">Account No: 259994715106</p>
                    <p style="margin-bottom: 0!important;">Branch: Velacherry</p>
                    <p style="margin-bottom: 0!important;">IFSC Code: INDB00060</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <img src="data:image;base64,<?= $footer_img; ?>" style="max-width: 100%;height: auto;">
        </div>
    </div>
    <!-- Include Bootstrap 5 JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


</body>

</html>