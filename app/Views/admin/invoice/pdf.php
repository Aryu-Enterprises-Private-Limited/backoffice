<html>
<?php //echo"<pre>";print_r($qntymore);
?>

<head>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"> -->
    <style>
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: 0.25rem;
        }

        .p-2 {
            padding: 0.5rem !important;
        }

        .flex-row {
            flex-direction: row !important;
        }

        .d-flex {
            display: flex !important;
        }

        .flex-column {
            flex-direction: column !important;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-accent-bg: transparent;
            --bs-table-striped-color: #212529;
            --bs-table-striped-bg: rgba(0, 0, 0, 0.05);
            --bs-table-active-color: #212529;
            --bs-table-active-bg: rgba(0, 0, 0, 0.1);
            --bs-table-hover-color: #212529;
            --bs-table-hover-bg: rgba(0, 0, 0, 0.075);
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            vertical-align: top;
            border-color: #dee2e6;
        }

        table {
            caption-side: bottom;
            border-collapse: collapse;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

        .content {
            font-size: 14px;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-color: inherit;
            border-style: solid;
            border-width: 0;
        }


        .table-borderless>:not(caption)>*>* {
            border-bottom-width: 0;
        }

        .add th {
            color: #5C5C5C;
            text-transform: uppercase;
            font-size: 15px;
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-3">
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <!-- <div class="d-flex flex-row p-2"> <img src="https://i.imgur.com/vzlPPh3.png" width="48"> -->
                    <div class="d-flex flex-column"> <span class="font-weight-bold">Tax Invoice No -</span> <small> <?= $invoice_no; ?></small> </div>
                    <div class="d-flex flex-column"> <span class="font-weight-bold">Date -</span> <small> <?= $invoice_date; ?></small> </div>
                </div>
                <hr>
                <div class="table-responsive p-2">
                    <table class="table table-borderless">
                        <tbody>
                            <tr class="add">
                                <td>To</td>
                                <td>From</td>
                            </tr>
                            <tr class="content">
                                <td class="font-weight-bold"><?= $to_name; ?> <br>Attn: John Smith Pymont <br>Australia</td>
                                <td class="font-weight-bold"><?= $from_name; ?> <br> Attn: John Right Polymont <br> USA</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="products p-2">
                    <table class="table table-borderless">
                        <thead>
                            <tr class="add head">
                                <td><strong>Description</strong></td>
                                <td><strong>Amount</strong></td>
                                <td><strong>Quantity</strong></td>
                                <!-- <td><strong>Date</strong></td> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $combinedArray = [];
                            $add_count = count($addmore);
                            $amnt_count =count($amntmore);
                            $qnty_count = count($qntymore);

                           
                            if($add_count == $amnt_count && $amnt_count == $qnty_count && $qnty_count == $add_count){
                                for ($i = 0; $i < count($addmore); $i++) {
                                    $combinedArray[] = [
                                        '0' => $addmore[$i],
                                        '1' => $amntmore[$i],
                                        '2' => $qntymore[$i]
                                    ];
                                }
                                foreach ($combinedArray as $item) { ?>
                                    <tr class="content add">
                                        <td class="text-center"><?= $item['0'] ?></td>
                                        <td class="text-center"><?= $item['1'] ?></td>
                                        <td class="text-center"><?= $item['2'] ?></td>
                                    </tr>
                                <?php }  
                            }else{ ?>
                                <td class="text-center">Norecord</td>
                          <?php  }?>
                            
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="products p-2">
                    <table class="table table-borderless">
                        <tbody>
                            <tr class="add">
                                <td></td>
                                <td>Subtotal</td>
                                <td>GST(10%)</td>
                                <td class="text-center">Total</td>
                            </tr>
                            <tr class="content">
                                <td></td>
                                <td>$40,000</td>
                                <td>2,500</td>
                                <td class="text-center">$42,500</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="address p-2">
                    <table class="table table-borderless">
                        <tbody>
                            <tr class="add">
                                <td>Bank Details</td>
                            </tr>
                            <tr class="content">
                                <td> Bank Name : ADS BANK <br> Swift Code : ADS1234Q <br> Account Holder : Jelly Pepper <br> Account Number : 5454542WQR <br> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>