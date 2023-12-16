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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/student_info/list' ?>"><?php echo  'Student Info'; ?> </a>
                        </li>
                        <?php if (isset($stu_info) && $stu_info->first_name) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $stu_info->first_name; ?>
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
                <form id="student_info_form" method="post" action="<?= (base_url(ADMIN_PATH . '/student_info/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($stu_info) && $stu_info->id) echo $stu_info->id; ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> First Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="first_name" id="first_name" value="<?php if (isset($stu_info->first_name)) echo $stu_info->first_name; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Last Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="last_name" id="last_name" value="<?php if (isset($stu_info->last_name)) echo $stu_info->last_name; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Email <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="email" id="email" value="<?php if (isset($stu_info->email)) echo $stu_info->email; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Current Status <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="radio" id="contactChoice1" name="current_status" <?php if (isset($stu_info->current_status)) {
                                                                                                    echo ($stu_info->current_status == 'student' ? 'checked' : '');
                                                                                                } ?> value="student" required>
                            <label for="contactChoice1"> Student </label>

                            <input type="radio" id="contactChoice2" name="current_status" <?php if (isset($stu_info->current_status)) {
                                                                                                    echo ($stu_info->current_status == 'job_seeker' ? 'checked' : '');
                                                                                                } ?> value="job_seeker">
                            <label for="contactChoice2"> Job Seeker </label>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">College/Company info <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <textarea class="form-control create-input"  required rows="3" name="clg_comp_info" id="clg_comp_info"><?php if (isset($stu_info) && $stu_info->clg_comp_info) echo $stu_info->clg_comp_info;  ?></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Parent / Guardian Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="par_guard_name" id="par_guard_name" value="<?php if (isset($stu_info->par_guard_name)) echo $stu_info->par_guard_name; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Parent/Guardian Phone <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="par_guard_phno" id="par_guard_phno" value="<?php if (isset($stu_info->par_guard_phno)) echo $stu_info->par_guard_phno; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Parent / Guardian occupation </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="parent_occ" id="parent_occ" value="<?php if (isset($stu_info->parent_occ)) echo $stu_info->parent_occ; ?>" >
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Reference Number 1 </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="ref_no_1" id="ref_no_1" value="<?php if (isset($stu_info->ref_no_1)) echo $stu_info->ref_no_1; ?>" >
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Reference Number 2 </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="ref_no_2" id="ref_no_2" value="<?php if (isset($stu_info->ref_no_2)) echo $stu_info->ref_no_2; ?>" >
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <?php if (isset($stu_info) && isset($stu_info->status) && $stu_info->status == '1') $sT = 'checked="checked"';
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
            if ($('#student_info_form').valid()) {
                $('.sbmtBtn').attr("disabled", true);
                $('#student_info_form').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>