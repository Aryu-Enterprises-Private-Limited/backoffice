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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/expense/list' ?>"><?php echo  'Expense'; ?> </a>
                        </li>
                        <?php if (isset($exp_info) && $exp_info->category_name) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $exp_info->category_name; ?>
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
                <form id="expense_form" method="post" action="<?= (base_url(ADMIN_PATH . '/expense/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($exp_info) && $exp_info->id) echo $exp_info->id; ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Date <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control create-input" name="date" id="date" value="<?php if (isset($exp_info) && $exp_info->date) echo $exp_info->date; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Invoice No/voucher <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="invoice_no" id="invoice_no" value="<?php if (isset($exp_info->invoice_no)) echo $exp_info->invoice_no; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Description <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <textarea required class="form-control create-input" rows="3" name="description" id="description"><?php if (isset($exp_info) && $exp_info->description) echo $exp_info->description;  ?></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Category <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-select form-control create-input" name="category_name" id="category_name" required>
                                <option value="">Select</option>
                                <?php foreach ($category_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($exp_info->category_id) && $exp_info->category_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>,<?php echo $value->category_name; ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->category_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Amount <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input numberonly" name="amount" id="amount" value="<?php if (isset($exp_info->amount)) echo $exp_info->amount; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <?php if (isset($exp_info) && isset($exp_info->status) && $exp_info->status == '1') $sT = 'checked="checked"';
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
            if ($('#expense_form').valid()) {
                $('.sbmtBtn').attr("disabled", true);
                $('#expense_form').submit();
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