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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/pay/list' ?>"><?php echo  'Pay'; ?> </a>
                        </li>
                        <?php if (isset($pay_info) && $pay_info->employee_name) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $pay_info->employee_name; ?>
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
                <form id="pay_form" method="post" action="<?= (base_url(ADMIN_PATH . '/pay/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($pay_info) && $pay_info->id) echo $pay_info->id; ?>">
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold">Employee Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select name="employee_name" class="form-control create-input required" id="employee_name">
                                <option value="">select</option>
                                <?php foreach ($employee_details as $key => $value) {
                                    $selected = '';
                                    if (isset($pay_info->employee_id) && $pay_info->employee_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id .','. ucfirst($value->first_name).' '.ucfirst($value->last_name); ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->first_name); ?> <?php echo ucfirst($value->last_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Monthly Salary <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="month_sal" id="month_sal" value="<?php if (isset($pay_info->month_sal)) echo $pay_info->month_sal; ?>" required>
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
            if ($('#pay_form').valid()) {
                $('#sbmtBtn').attr("disabled", true);
                $('#pay_form').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>