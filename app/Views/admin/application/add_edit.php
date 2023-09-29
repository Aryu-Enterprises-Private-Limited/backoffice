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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/application/list' ?>"><?php echo  'Application'; ?> </a>
                        </li>
                        <?php if (isset($app_info) && $app_info->app_status) { ?>
                            <li class="breadcrumb-item text-decoration-none">
                                <?php echo $app_info->app_status; ?>
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
                <form id="app_form" method="post" action="<?= (base_url(ADMIN_PATH . '/application/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($app_info) && $app_info->id) echo $app_info->id; ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Application Status<span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="app_status" id="app_status" value="<?php if (isset($app_info->app_status)) echo $app_info->app_status; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <?php if (isset($app_info) && isset($app_info->status) && $app_info->status == '1') $sT = 'checked="checked"';
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
            if ($('#app_form').valid()) {
                $('.sbmtBtn').attr("disabled", true);
                $('#app_form').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>