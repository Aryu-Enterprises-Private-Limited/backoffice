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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/candidates/list' ?>"><?php echo  'Candidate'; ?> </a>
                        </li>
                        <?php if (isset($candidates_info) && $candidates_info->first_name) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $candidates_info->first_name; ?>
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
                <form id="candidates_form" method="post" action="<?= (base_url(ADMIN_PATH . '/candidates/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($candidates_info) && $candidates_info->id) echo $candidates_info->id; ?>">
                    <?php
                    $req = 'required';
                    if (isset($candidates_info) && $candidates_info->id) {
                        $req = '';
                    }
                    ?>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Date </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="date" id="date" value="<?php if (isset($candidates_info->date)) echo $candidates_info->date;
                                                                                                                else echo date("Y-m-d"); ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> First Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="first_name" id="first_name" value="<?php if (isset($candidates_info->first_name)) echo $candidates_info->first_name; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Last Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="last_name" id="last_name" value="<?php if (isset($candidates_info->last_name)) echo $candidates_info->last_name; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Location <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="location" id="location" value="<?php if (isset($candidates_info->location)) echo $candidates_info->location; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> CV/Resume <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control create-input" name="resume" id="resume" value="" <?= $req; ?>>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Contact Number <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="contact_no" id="contact_no" value="<?php if (isset($candidates_info->contact_no)) echo $candidates_info->contact_no; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Email <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="email" id="email" value="<?php if (isset($candidates_info->email)) echo $candidates_info->email; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Job Opening <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-select form-control create-input" name="job_opening_id" id="job_opening_id" required>
                                <option value="">select</option>
                                <?php foreach ($job_open_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($candidates_info->job_opening_id) && $candidates_info->job_opening_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->jobs_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Application Status <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-select form-control create-input" name="application_status_id" id="application_status_id" required>
                                <option value="">select</option>
                                <?php foreach ($app_sts_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($candidates_info->application_status_id) && $candidates_info->application_status_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->app_status); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Interview Status <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-select form-control create-input" name="interview_status_id" id="interview_status_id" required>
                                <option value="">select</option>
                                <?php foreach ($int_sts_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($candidates_info->interview_status_id) && $candidates_info->interview_status_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->interview_sts); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Stage <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-select form-control create-input" name="stage_id" id="stage_id" required>
                                <option value="">select</option>
                                <?php foreach ($stage_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($candidates_info->stage_id) && $candidates_info->stage_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->stage_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <?php if (isset($candidates_info) && isset($candidates_info->background_check) && $candidates_info->background_check == '1') $sT = 'checked="checked"';
                        else $sT = ''; ?>
                        <label class="col-sm-2 col-form-label fw-bold">Background Check</label>
                        <div class="form-check form-switch col-sm-10">
                            <input class="form-check-input form-control" type="checkbox" name="background_check" <?php echo $sT; ?>>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Source <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="source" id="source" value="<?php if (isset($candidates_info->source)) echo $candidates_info->source; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Reason for Rejection <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-select form-control create-input" name="reason_rejection_id" id="reason_rejection_id" required>
                                <option value="">select</option>
                                <?php foreach ($rr_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($candidates_info->reason_rejection_id) && $candidates_info->reason_rejection_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->reason_for_rej); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Reason </label>
                        <div class="col-sm-10">
                            <textarea class="form-control create-input" rows="3" name="reason" id="reason"><?php if (isset($candidates_info) && $candidates_info->reason) echo $candidates_info->reason;  ?></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <?php if (isset($candidates_info) && isset($candidates_info->status) && $candidates_info->status == '1') $sT = 'checked="checked"';
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (session('success_message')) : ?>
    <script>
        toastr.success('<?= session('success_message') ?>');
    </script>
<?php endif; ?>
<?php if (session('error_message')) : ?>
    <script>
        toastr.error('<?= session('error_message') ?>');
    </script>
<?php endif; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $(".sbmtBtn").click(function(evt) {
            if ($('#candidates_form').valid()) {
                $('#sbmtBtn').attr("disabled", true);
                $('#candidates_form').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>