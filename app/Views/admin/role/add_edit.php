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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/role/list' ?>"><?php echo  'Role'; ?> </a>
                        </li>
                        <?php if (isset($role_info) && $role_info->role_name) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $role_info->role_name; ?>
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
                <form id="role_form" method="post" action="<?= (base_url(ADMIN_PATH . '/role/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($role_info) && $role_info->id) echo $role_info->id; ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Role Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="role_name" id="role_name" value="<?php if (isset($role_info->role_name)) echo $role_info->role_name; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold">Department Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select name="dept_name" class="form-control create-input required" id="dept_name">
                                <option value="">select</option>
                                <?php foreach ($dept_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($role_info->department_id) && $role_info->department_id == $value->id) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo $value->id; ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->department_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <?php if (isset($role_info) && isset($role_info->status) && $role_info->status == '1') $sT = 'checked="checked"';
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
            if ($('#role_form').valid()) {
                $('#sbmtBtn').attr("disabled", true);
                $('#role_form').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>