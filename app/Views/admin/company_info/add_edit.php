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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/company_info/list' ?>"><?php echo  'Company Info'; ?> </a>
                        </li>
                        <?php if (isset($company_info) && $company_info->company_title) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $company_info->company_title; ?>
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
                <form id="company_form" method="post" action="<?= (base_url(ADMIN_PATH . '/company_info/update'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($company_info) && $company_info->id) echo $company_info->id; ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Company Title <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="company_title" id="company_title" value="<?php if (isset($company_info->company_title)) echo $company_info->company_title; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">User Id / User Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="user_id_name" id="user_id_name" value="<?php if (isset($company_info->user_id_name)) echo $company_info->user_id_name; ?>" required>
                        </div>
                    </div>
                    <?php

                    if (isset($company_info)) {
                    ?>
                        <div class="mb-3 row">
                        <div class="mb-3 row hide_show">
                            <label class="col-sm-2 col-form-label fw-bold">Password <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control create-input" name="password" value="<?php if (isset($company_info->original_password)) echo $company_info->original_password; ?>"  required>
                            </div>
                        </div>
                            <div class="col-sm-4">
                            </div>
                            <div class="col-sm-8">
                                <button type="button" class="btn butn-submit text-white show_password"> Show Password</button>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label fw-bold">Password <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control create-input" name="password" value="<?php if (isset($company_info->original_password)) echo $company_info->original_password; ?>" id="password" required>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Links <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="links" id="links" value="<?php if (isset($company_info->links)) echo $company_info->links; ?>" required>
                        </div>
                    </div>
                    <!-- <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Value <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="value" id="value" value="<?php if (isset($company_info->value)) echo $company_info->value; ?>" required>
                        </div>
                    </div> -->
                    <div class="mb-3 row">
                        <?php if (isset($company_info) && isset($company_info->status) && $company_info->status == '1') $sT = 'checked="checked"';
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
<div class="modal" tabindex="-1" id="password_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="show_data">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label fw-bold"> Password </label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="password" id="password" required>
                        <span id="error_show"></span>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirm_password">Submit</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
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
             $(".hide_show").hide(); 

        const myModal = new bootstrap.Modal('#password_modal');
        $('body').on("click", ".show_password", function() {
            myModal.show();
        });

        $('body').on("click", "#confirm_password", function() {
           var  password = $("#password").val();
           var url = "<?php echo base_url(); ?>admin/company_info/check_password";
           if(password == ''){
            $("#error_show").append("<p class='text-danger'>Password Field is Required </p>");
           }else{
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    'password': password,
                },
                dataType: 'json',
                success: function(res) {
                    // console.log(res.status);
                    if(res.status == 1){
                        myModal.hide();
                        $(".hide_show").show(); 
                        $(".show_password").hide();
                        toastr.success(res.response);
                        // location.reload();
                    }else{
                        location.reload();
                    }
                }
            });
           }
        });

        $(".sbmtBtn").click(function(evt) {
            if ($('#company_form').valid()) {
                $('.sbmtBtn').attr("disabled", true);
                $('#company_form').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>