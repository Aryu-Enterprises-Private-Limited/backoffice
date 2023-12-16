<!-- Start content -->
<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item bread-home"><a href="<?= '/' . ADMIN_PATH . '/dashboard' ?>"><i class="fa fa-home me-0" aria-hidden="true"></i></a></li>
                        <li class="breadcrumb-item">
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/fees/list' ?>"><?php echo  'Payment'; ?> </a>
                        </li>
                        <?php if (isset($fee_info) && $fee_info->student_name) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $fee_info->student_name; ?>
                            </li>
                            <li class="breadcrumb-item active">
                                <?php echo 'Edit'; ?>
                            </li>
                        <?php } else { ?>
                            <li class="breadcrumb-item active">
                                <?php echo 'Add New'; ?>
                            </li>
                        <?php } ?>
                    </ol>
                    <hr>
                    <h3><?= $title;  ?></h3>
                </div>
            </div>
            <div class="ms-5 card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold"> Date </label>
                            <div class="col-sm-8">
                                <p><?= $fee_info->date; ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold"> Student Name </label>
                            <div class="col-sm-8">
                                <p><?= $fee_info->student_name; ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold"> Course Name </label>
                            <div class="col-sm-8">
                                <p><?= $fee_info->course_name; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold"> Total Fee </label>
                            <div class="col-md-8">
                                <p><?= $fee_info->total_fee; ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold"> Date & Amount Paid </label>
                            <div class="col-md-8">
                                <?php 
                                $totalAmount = 0;
                                if(isset($payment_info) && $payment_info !=''){
                                    $totalAmount = 0;
                                    $paymentDetails = json_decode($payment_info->payment_details, true);
                                    // print_r($paymentDetails);
                                    foreach ($paymentDetails as $date => $amount) {
                                        echo "{$date} : {$amount}<br>";
                                        $totalAmount += (int)$amount;
                                    }
                                    echo "Total Amount: {$totalAmount}<br><br>";
                                }else{ ?>
                                        <p>-</p>
                              <?php  }
                                ?>
                                <!--  -->
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold"> Balance Due </label>
                            <div class="col-md-8">
                                <?php 
                                $total_fee = $fee_info->total_fee;
                                $total_paid_fee = $totalAmount;
                                $bal_due = $total_fee - $total_paid_fee;
                                ?>
                                <p><?= $bal_due; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
            if($bal_due > 0 ){

            
            ?>
            <div class="create-label mt-5">
                <form id="fees_pay_form" method="post" action="<?= (base_url(ADMIN_PATH . '/fees/update_payment'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($payment_info) && $payment_info->id) echo $payment_info->id; ?>">
                    <input type="hidden" id="student_id" name="student_id" value="<?php if (isset($fee_info) && $fee_info->student_id) echo $fee_info->student_id; ?>">
                    <input type="hidden" id="student_name" name="student_name" value="<?php if (isset($fee_info) && $fee_info->student_name) echo $fee_info->student_name; ?>">
                    <input type="hidden" id="course_id" name="course_id" value="<?php if (isset($fee_info) && $fee_info->course_id) echo $fee_info->course_id; ?>">
                    <input type="hidden" id="course_name" name="course_name" value="<?php if (isset($fee_info) && $fee_info->course_name) echo $fee_info->course_name; ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Date <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control create-input" name="date" id="date" value="" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Amount Paid <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="amount_paid" id="amount_paid" value="" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Balance Due </label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control create-input" value="<?= $bal_due; ?>">
                        </div>
                    </div>


                    <button type="button" class="btn butn-submit text-white sbmtBtn" id="btn">Submit</button>
                </form>
            </div>
        <?php } ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".sbmtBtn").click(function(evt) {
            if ($('#fees_pay_form').valid()) {
                $('.sbmtBtn').attr("disabled", true);
                $('#fees_pay_form').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>