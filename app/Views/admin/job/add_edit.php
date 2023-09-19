<!-- Start content -->
<?= $this->extend('layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="/assets/richtexteditor/rte_theme_default.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/job/list' ?>"><?php echo  'Job'; ?> </a>
                        </li>
                        <?php if (isset($job_info) && $job_info->jobs_name) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $job_info->jobs_name; ?>
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
                <form id="job_form" method="post" action="<?= (base_url(ADMIN_PATH . '/job/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($job_info) && $job_info->id) echo $job_info->id; ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Job Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="job_name" id="job_name" value="<?php if (isset($job_info->jobs_name)) echo $job_info->jobs_name; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Job Description <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <!-- <div id="div_editor1">
                        </div> -->
                            <textarea class="form-control create-input" rows="3" name="job_desc" id="job_desc" required><?php if (isset($job_info) && $job_info->job_desc) echo $job_info->job_desc;  ?></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Job Budget <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="job_budget" id="job_budget" value="<?php if (isset($job_info->job_budget)) echo $job_info->job_budget; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold">Job Type <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select name="job_type" class="form-control create-input required" id="job_type">
                                <option value="">select</option>
                                <?php foreach ($job_type_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($job_info->job_type_id) && $job_info->job_type_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->job_type_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Job Requirement <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-control js-example-tokenizer" multiple="multiple" name="job_req[]" required>
                                <option value="">select</option>
                                <?php
                                if(isset($job_info->job_requirement)){
                                $req_arr = explode(",", $job_info->job_requirement);
                                foreach ($req_arr as $key => $value) {
                                ?>
                                    <option selected="selected"><?= $value; ?></option>
                                <?php   } }?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <?php if (isset($job_info) && isset($job_info->status) && $job_info->status == '1') $sT = 'checked="checked"';
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

<script type="text/javascript" src="/assets/richtexteditor/rte.js"></script>
<script type="text/javascript" src="/assets/richtexteditor/plugins/all_plugins.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".sbmtBtn").click(function(evt) {
            if ($('#job_form').valid()) {
                $('#sbmtBtn').attr("disabled", true);
                $('#job_form').submit();
            }
            // var tagsArray = [];
            // $("#tags span").each(function() {
            //     tagsArray.push($(this).text());
            // });
            // $('#job_req').val(tagsArray.join(','));

            // return false;
        });

        $(".js-example-tokenizer").select2({
            tags: true,
            tokenSeparators: [',', ' ']
        })

        var editor1 = new RichTextEditor("#job_desc");
        //editor1.setHTMLCode("Use inline HTML or setHTMLCode to init the default content.");

    });
</script>
<?= $this->endSection() ?>