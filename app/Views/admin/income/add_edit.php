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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/income/list' ?>"><?php echo  'Income'; ?> </a>
                        </li>
                        <?php if (isset($income_info) && $income_info->company_name) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $income_info->company_name; ?>
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
                <form id="income_form" method="post" action="<?= (base_url(ADMIN_PATH . '/income/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($income_info) && $income_info->id) echo $income_info->id; ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Date <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control create-input" name="date" id="date" value="<?php if (isset($income_info) && $income_info->date) echo $income_info->date; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Invoice No/voucher </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="invoice_no" id="invoice_no" value="<?php if (isset($income_info->invoice_no)) echo $income_info->invoice_no; ?>" >
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Company Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="company_name" id="company_name" value="<?php if (isset($income_info->company_name)) echo $income_info->company_name; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Billed Account <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-select form-control create-input" name="billed_acc_name" id="billed_acc_name" required>
                                <option value="">Select</option>
                                <?php foreach ($billed_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($income_info->billed_acc_id) && $income_info->billed_acc_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>,<?php echo $value->billed_acc_name; ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->billed_acc_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Amount <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input numberonly" name="amount" id="amount" value="<?php if (isset($income_info->amount)) echo $income_info->amount; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Currency Code<span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="currency_code" id="currency_code" value="<?php if (isset($income_info->currency_code)) echo $income_info->currency_code; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <?php if (isset($income_info) && isset($income_info->status) && $income_info->status == '1') $sT = 'checked="checked"';
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
            if ($('#income_form').valid()) {
                $('.sbmtBtn').attr("disabled", true);
                $('#income_form').submit();
            }
        });
        $('.numberonly').keypress(function(e) {
            var charCode = (e.which) ? e.which : event.keyCode
            if (String.fromCharCode(charCode).match(/[^0-9.]/g))
                return false;
        });
    });
</script>
<?= $this->endSection() ?>