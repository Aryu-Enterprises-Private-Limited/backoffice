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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/emp_bank_info/list' ?>"><?php echo  'Employee Bank info'; ?> </a>
                        </li>
                        <?php if (isset($employee_bank_details) && $employee_bank_details->employee_name) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $employee_bank_details->employee_name; ?>
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

            <div class="create-label">
                <form id="emp_bank_form" method="post" action="<?= (base_url(ADMIN_PATH . '/emp_bank_info/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($employee_bank_details) && $employee_bank_details->id) echo $employee_bank_details->id; ?>">
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold">Employee Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select name="employee_name" class="form-control create-input required" id="employee_name">
                                <option value="">select</option>
                                <?php foreach ($employee_details as $key => $value) {
                                    $selected = '';
                                    if (isset($employee_bank_details->employee_id) && $employee_bank_details->employee_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id . ',' . ucfirst($value->first_name) . ' ' . ucfirst($value->last_name); ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->first_name); ?> <?php echo ucfirst($value->last_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Account Number <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="acc_no" id="acc_no" value="<?php if (isset($employee_bank_details->acc_no)) echo $employee_bank_details->acc_no; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">IFSC Code <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="ifsc_code" id="ifsc_code" value="<?php if (isset($employee_bank_details->ifsc_code)) echo $employee_bank_details->ifsc_code; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Account Type <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="acc_type" id="acc_type" value="<?php if (isset($employee_bank_details->acc_type)) echo $employee_bank_details->acc_type; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Branch Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="branch_name" id="branch_name" value="<?php if (isset($employee_bank_details->branch_name)) echo $employee_bank_details->branch_name; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Employee Status <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="radio" id="contactChoice1" name="employee_sts" <?php if (isset($employee_bank_details->employee_sts)) {
                                                                                            echo ($employee_bank_details->employee_sts == 'current_employee' ? 'checked' : '');
                                                                                        } ?> value="current_employee" required>
                            <label for="contactChoice1"> Current Employee </label>

                            <input type="radio" id="contactChoice2" name="employee_sts" <?php if (isset($employee_bank_details->employee_sts)) {
                                                                                            echo ($employee_bank_details->employee_sts == 'relieved_employee' ? 'checked' : '');
                                                                                        } ?> value="relieved_employee">
                            <label for="contactChoice2"> Relieved Employee </label>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <?php if (isset($pay_info) && isset($pay_info->status) && $pay_info->status == '1') $sT = 'checked="checked"';
                        else $sT = ''; ?>
                        <label class="col-sm-2 col-form-label fw-bold">Status</label>
                        <div class="form-check form-switch col-sm-10">
                            <input class="form-check-input form-control" type="checkbox" name="status" <?php echo $sT; ?>>
                        </div>
                    </div>
                    <button type="button" class="btn butn-submit text-white sbmtBtn" id="btn">Submit</button>
                </form>
            </div>
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
            if ($('#emp_bank_form').valid()) {
                $('#sbmtBtn').attr("disabled", true);
                $('#emp_bank_form').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>