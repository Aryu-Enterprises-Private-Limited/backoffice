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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/links/list' ?>"><?php echo  'Links'; ?> </a>
                        </li>
                        <?php if (isset($links_info) && $links_info->department_name) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $links_info->department_name; ?>
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
                <form id="links_form" method="post" action="<?= (base_url(ADMIN_PATH . '/links/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($links_info) && $links_info->id) echo $links_info->id; ?>">
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold"> Department <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select name="department_name" class="form-control create-input required" id="department_name">
                                <option value="">Select</option>
                                <?php foreach ($department_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($links_info->department_id) && $links_info->department_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>,<?php echo $value->department_name; ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->department_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Links Tags <span class="text-danger">*</span></label>
                        <div class="col-sm-10 reduce_len">
                            <select class="form-control js-example-tokenizer" multiple="multiple" name="links_tags[]" required>
                                <option value="">select</option>
                                <?php
                                if (isset($links_info->links_tags)) {
                                    $req_arr = explode(",", $links_info->links_tags);
                                    foreach ($req_arr as $key => $value) {
                                ?>
                                        <option selected="selected"><?= $value; ?></option>
                                <?php   }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Link <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="link" id="link" value="<?php if (isset($links_info->link)) echo $links_info->link; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <?php if (isset($links_info) && isset($links_info->status) && $links_info->status == '1') $sT = 'checked="checked"';
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
            if ($('#links_form').valid()) {
                $('.sbmtBtn').attr("disabled", true);
                $('#links_form').submit();
            }
        });

        $(".js-example-tokenizer").select2({
            tags: true,
            tokenSeparators: [',', ' ']
        })

    });
</script>
<?= $this->endSection() ?>