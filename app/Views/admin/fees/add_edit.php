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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/fees/list' ?>"><?php echo  'Fees'; ?> </a>
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

            <div class="create-label">
                <form id="fees_form" method="post" action="<?= (base_url(ADMIN_PATH . '/fees/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($fee_info) && $fee_info->id) echo $fee_info->id; ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Date <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control create-input" name="date" id="date" value="<?php if (isset($fee_info) && $fee_info->date) echo $fee_info->date; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold"> Student Name  <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select name="stu_details" class="form-control create-input required" id="stu_details">
                                <option value="">select</option>
                                <?php foreach ($stu_details as $key => $value) {
                                    $selected = '';
                                    if (isset($fee_info->student_id) && $fee_info->student_id == $value->id) {
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
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold"> Courses Name  <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select name="course_name" class="form-control create-input required" id="course_name">
                                <option value="">select</option>
                                <?php foreach ($course_details as $key => $value) {
                                    $selected = '';
                                    if (isset($fee_info->course_id) && $fee_info->course_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id . ',' . ucfirst($value->course_name) ; ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->course_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Total Fee<span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="total_fee" id="total_fee" value="<?php if (isset($fee_info->total_fee)) echo $fee_info->total_fee; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">No Of Installment </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="no_of_installment" id="no_of_installment" value="<?php if (isset($fee_info->no_of_installment)) echo $fee_info->no_of_installment; ?>" >
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <?php if (isset($fee_info) && isset($fee_info->status) && $fee_info->status == '1') $sT = 'checked="checked"';
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
            if ($('#fees_form').valid()) {
                $('.sbmtBtn').attr("disabled", true);
                $('#fees_form').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>