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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/crm/list' ?>"><?php echo  'CRM'; ?> </a>
                        </li>
                        <?php if (isset($info) && $info->id) { ?>
                            <li class="breadcrumb-item text-decoration-none">
                                <?php echo $info->project_details; ?>
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
                <form id="crm_form" method="post" action="<?= (base_url(ADMIN_PATH . '/crm/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($info) && $info->id) echo $info->id; ?>">
                    <?php
                    $req = 'required';
                    if (isset($info) && $info->id) {
                        $req = '';
                    }
                    ?>
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold">Lead <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select name="lead" class="form-control create-input required" id="lead">
                                <option value="">select</option>
                                <?php foreach ($lms_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($info->lead) && $info->lead == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->first_name); ?> <?php echo ucfirst($value->last_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Project Details <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="project_details" id="project_details" value="<?php if (isset($info->project_details)) echo $info->project_details; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Price <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="price" id="price" value="<?php if (isset($info->price)) echo $info->price; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Document Upload <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control create-input" name="crm_file" id="crm_file" value="<?php if (isset($info->document_name)) echo $info->document_name; ?>" <?= $req; ?>>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <?php if (isset($info) && isset($info->status) && $info->status == '1') $sT = 'checked="checked"';
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
            if ($('#crm_form').valid()) {
                $('#sbmtBtn').attr("disabled", true);
                $('#crm_form').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>