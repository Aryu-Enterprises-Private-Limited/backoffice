<!-- Start content -->
<?= $this->extend('layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item bread-home"><a href="<?= '/' . ADMIN_PATH . '/dashboard' ?>"><i class="fa fa-home me-0" aria-hidden="true"></i></a></li>
                        <li class="breadcrumb-item">
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/public_holiday/list' ?>"><?php echo  'Pay'; ?> </a>
                        </li>
                        <?php if (isset($pholiday_info) && $pholiday_info->reason) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $pholiday_info->reason; ?>
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
                <form id="holiday_form" method="post" action="<?= (base_url(ADMIN_PATH . '/public_holiday/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($pholiday_info) && $pholiday_info->id) echo $pholiday_info->id; ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Current Year </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="curr_year" id="curr_year" value="<?php if (isset($pholiday_info->current_year)) echo $pholiday_info->current_year;
                                                                                                                        else echo date("Y");  ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Date <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="date" id="date" placeholder="Enter date Range" value="<?php if (isset($pholiday_info->date)) echo $pholiday_info->date;  ?>" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">reason <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="reason" id="reason" value="<?php if (isset($pholiday_info->reason)) echo $pholiday_info->reason; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <?php if (isset($pholiday_info) && isset($pholiday_info->status) && $pholiday_info->status == '1') $sT = 'checked="checked"';
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

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".sbmtBtn").click(function(evt) {
            if ($('#holiday_form').valid()) {
                $('.sbmtBtn').attr("disabled", true);
                $('#holiday_form').submit();
            }
        });

        $("#date").datepicker();
    });
</script>
<?= $this->endSection() ?>