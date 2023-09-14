<!-- Start content -->
<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item bread-home"><a href="<?= '/' . ADMIN_PATH . '/dashboard' ?>"><i class="fa fa-home" aria-hidden="true"></i></a></li>
                        <li class="breadcrumb-item">
                            <a href="<?= '/' . ADMIN_PATH . '/reason_rejection/list' ?>"><?php echo  'Reason For rejection'; ?> </a>
                        </li>
                        <?php if (isset($rr_info) && $rr_info->reason_for_rej) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $rr_info->reason_for_rej; ?>
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
                <form id="rr_form" method="post" action="<?= (base_url(ADMIN_PATH . '/reason_rejection/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($rr_info) && $rr_info->id) echo $rr_info->id; ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Reason& Rejection <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="reason_for_rej" id="reason_for_rej" value="<?php if (isset($rr_info->reason_for_rej)) echo $rr_info->reason_for_rej; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <?php if (isset($rr_info) && isset($rr_info->status) && $rr_info->status == '1') $sT = 'checked="checked"';
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
            if ($('#rr_form').valid()) {
                $('#sbmtBtn').attr("disabled", true);
                $('#rr_form').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>