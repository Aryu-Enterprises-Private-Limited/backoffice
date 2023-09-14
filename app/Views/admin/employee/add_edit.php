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
                            <a href="<?= '/' . ADMIN_PATH . '/employee/list' ?>"><?php echo  'Employee'; ?> </a>
                        </li>
                        <?php if (isset($info) && $info->first_name) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $info->first_name; ?>
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
            <form method="post" action="" id="employee_form" autocomplete="off" enctype="multipart/form-data">

                <div class="create-labels" id="personal">
                    <input type="hidden" id="id" class="form-control" name="id" value="<?php if (isset($info) && $info->id) echo $info->id; ?>">
                    <?php
                    $read = '';
                    if (isset($info) && $info->id) {
                        $read = 'readonly';
                    }
                    ?>
                    <div class="mb-3">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter The first Name" id="first_name" name="first_name" <?= $read; ?> value="<?php if (isset($info) && $info->first_name) echo $info->first_name; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter The Last Name" name="last_name" id="last_name" <?= $read; ?> value="<?php if (isset($info) && $info->last_name) echo $info->last_name; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date_of_birth" id="date_of_birth" value="<?php if (isset($info) && $info->dob) echo $info->dob; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" placeholder="Enter The Phone No" name="phone_number" id="phone_number" value="<?php if (isset($info) && $info->phone) echo $info->phone; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" placeholder="Enter The Email" name="email" <?= $read; ?> id="email" value="<?php if (isset($info) && $info->email) echo $info->email; ?>" required>
                    </div>
                    <?php if (!isset($info)) { ?>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label> Password <span class="text-danger">*</span></label>
                                <input name="password" id="password" type="password" class="form-control" placeholder="Password" minlength="6" />
                            </div>
                            <div class="col-md-6">
                                <label> Confirm Password <span class="text-danger">*</span></label>
                                <input name="confirmpassword" id="confirmpassword" type="password" class="form-control" equalTo="#password" placeholder="Confirm Password" minlength="6" />
                            </div>
                        </div>
                    <?php } ?>
                    <div class="mb-3">
                        <label class="form-label"> Role <span class="text-danger">*</span></label>
                        <select class="form-select form-control" name="role_id" id="role_id" required>
                            <option value="">select</option>
                            <?php foreach ($role_opt as $key => $value) {
                                $selected = '';
                                if (isset($info->role_id) && $info->role_id == $value->id) {
                                    $selected = 'selected';
                                }
                            ?>
                                <option value="<?php echo $value->id; ?>" <?= $selected; ?>>
                                    <?php echo ucfirst($value->role_name); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"> Department <span class="text-danger">*</span></label>
                        <select class="form-select form-control" name="department_id" id="department_id" required>
                            <option value="">select</option>
                            <?php foreach ($dept_opt as $key => $value) {
                                $selected = '';
                                if (isset($info->department_id) && $info->department_id == $value->id) {
                                    $selected = 'selected';
                                }
                            ?>
                                <option value="<?php echo $value->id; ?>" <?= $selected; ?>>
                                    <?php echo ucfirst($value->department_name); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" rows="3" name="address" id="address" required><?php if (isset($info) && $info->address) echo $info->address;  ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pin Code <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" placeholder="Enter Pin Code" name="pin_code" id="pin_code" value="<?php if (isset($info) && $info->pin_code) echo $info->pin_code; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter The City" name="city" id="city" value="<?php if (isset($info) && $info->city) echo $info->city; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">State <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter The State" name="state" id="state" value="<?php if (isset($info) && $info->state) echo $info->state; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Blood Group <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter The Blood Group" name="blood_group" id="blood_group" value="<?php if (isset($info) && $info->blood_grp) echo $info->blood_grp; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Aadhar No <span class="text-danger">*</span></label>
                        <input type="number" <?= $read; ?> class="form-control" placeholder="Enter The Aadhar no" name="aadhar_no" id="aadhar_no" value="<?php if (isset($info) && $info->aadhar_no) echo $info->aadhar_no; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pan No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" <?= $read; ?> placeholder="Enter The Pan No" name="pan_no" id="pan_no" value="<?php if (isset($info) && $info->pan_no) echo $info->pan_no; ?>" required>
                    </div>
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary but next_1" data-ftype="step_1" id="next_1">Next</button>
                    </div>
                </div>
                <div class="p" id="emergency">
                    <input type="hidden" id="id" class="form-control" name="id" value="<?php if (isset($info) && $info->id) echo $info->id; ?>">
                    <div class="square">
                        <h2 class="text-center">Emergency Contact Information </h2>
                        <div class="mb-3">
                            <label class="form-label">Relationship <span class="text-danger">*</span></label>
                            <select class="form-select form-control" name="relationship" id="relationship" required>
                                <option selected>Choose...</option>
                                <option value="father" <?php if (isset($info)) echo ($info->relationship == 'father') ? "selected" : ""
                                                        ?>>Father</option>
                                <option value="mother" <?php if (isset($info)) echo ($info->relationship == 'mother') ? "selected" : ""
                                                        ?>>MOther</option>
                                <option value="brother" <?php if (isset($info)) echo ($info->relationship == 'brother') ? "selected" : ""
                                                        ?>>Brother</option>
                                <option value="wife" <?php if (isset($info)) echo ($info->relationship == 'wife') ? "selected" : ""
                                                        ?>>Wife</option>
                                <option value="others" <?php if (isset($info)) echo ($info->relationship == 'others') ? "selected" : ""
                                                        ?>>Others</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter The Name" name="r_name" id="r_name" value="<?php if (isset($info) && $info->r_name) echo $info->r_name; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" placeholder="Enter The Phone No" name="r_phone" id="r_phone" value="<?php if (isset($info) && $info->r_phone) echo $info->r_phone; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="@email" class="form-select" placeholder="Enter The Email " name="r_email" id="r_email" value="<?php if (isset($info) && $info->r_email) echo $info->r_email; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="3" name="r_address" id="r_address"><?php if (isset($info) && $info->r_address) echo $info->r_address;  ?></textarea>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary but next_2" data-ftype="step_2" id="nexts">Next</button>
                        </div>
                    </div>
                </div>

                <div class="square" id="employe_information">
                    <h2 class="text-center">Previous Employer Information </h2>
                    <div class="mb-3">
                        <label class="form-label">Fresher/Experience <span class="text-danger">*</span></label>
                        <select class="form-select form-control" name="fresher_experience">
                            <option selected>Choose</option>
                            <option value="fresher" <?php if (isset($info)) echo ($info->work_exp == 'fresher') ? "selected" : ""
                                                    ?>>Fresher</option>
                            <option value="experience" <?php if (isset($info)) echo ($info->work_exp == 'experience') ? "selected" : ""
                                                        ?>>Experience</option>

                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CV/Resume <span class="text-danger">*</span></label>
                        <input class="form-control form-control-sm" id="cv_resume" type="file" name="cv_resume" value="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="text-danger">*</span></label>
                        <textarea class="form-control" rows="3" name="notes"><?php if (isset($info) && $info->notes) echo $info->notes;  ?></textarea>
                    </div>
                    <div class="mb-3">
                        <?php if (isset($info) && isset($info->status) && $info->status == '1') $sT = 'checked="checked"';
                        else $sT = ''; ?>
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input form-control" type="checkbox" name="status" <?php echo $sT; ?>>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary but sbmtBtn" data-ftype="step_3">Submit</button>
                    </div>
                </div>
            </form>
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
        $('#emergency').hide();
        $('#employe_information').hide();

        $('body').on("click", ".next_1,.next_2,.sbmtBtn", function(e) {
            e.preventDefault();
            var data = new FormData();
            var act_url = '<?= "/admin/employee/form_valid" ?>';
            // alert(act_url);
            var type = $(this).attr('data-ftype');
            id_name = '';
            if (type == 'step_1') {
                var id_name = 'personal';
            } else if (type == 'step_2') {
                var id_name = 'emergency';
            } else {
                var id_name = 'employe_information';
            }
            data.append('type', type);

            $('#' + id_name + ' .form-control').each(function(i, e) {
                data.append($(e).attr('name'), $(e).val());
            });

            if (type == 'step_3') {
                $('.sbmtBtn').prop('disabled', true);
                $('#personal .form-control:hidden').each(function(i, e) {
                    data.append($(e).attr('name'), $(e).val());
                });
                $('#emergency .form-control:hidden').each(function(i, e) {
                    data.append($(e).attr('name'), $(e).val());
                });
                $('#employe_information .form-control:hidden').each(function(i, e) {
                    data.append($(e).attr('name'), $(e).val());
                    // $("cv_resume").remove();
                    // data.append('cv_resume' , '');
                });

                var files = $('#cv_resume')[0].files;
                if (files.length > 0) {
                    data.append('cv_resume', files[0]);
                }

            }
            // console.log(data);
            // return false;
            $.ajax({
                type: 'post',
                url: act_url,
                data: data,
                // dataType: 'json',
                contentType: false,
                processData: false,
                success: function(res) {
                    if (id_name == 'emergency') {
                        $('#' + id_name).hide();
                        $('#employe_information').show();

                    } else if (id_name == 'personal') {
                        $('#' + id_name).hide();
                        $('#emergency').show();

                    } else {
                        // if(res.status == 0){
                        window.location.href = res.redirect;
                        // }
                    }
                    // console.log(res[0].id);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // console.dir(jqXHR.responseJSON.response);
                    if (jqXHR.responseJSON.response) {
                        $('.sbmtBtn').prop('disabled', false);
                        $('.error.text-danger').remove();
                        $('#' + id_name + ' .form-control').each(function(i, e) {
                            var spanV = $('<span class="error text-danger">').text(jqXHR.responseJSON.response[$(e).attr('name')]);
                            $(e).parent().append(spanV);
                        });
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>