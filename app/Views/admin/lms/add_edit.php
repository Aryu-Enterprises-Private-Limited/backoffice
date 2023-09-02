<!-- Start content -->
<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <h3><?= $title;  ?></h3>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn  butn-back text-white">Back</button>
                </div>

            </div>


            <div class="create-label">
                <form id="lms_form" method="post" action="<?= (base_url(ADMIN_PATH . '/lms/update'))  ?>" autocomplete="off">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($info) && $info->id) echo $info->id; ?>">
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold">Firstname <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="first_name" id="first_name" value="<?php if (isset($info->first_name)) echo $info->first_name; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Lastname <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="last_name" id="last_name" value="<?php if (isset($info->last_name)) echo $info->last_name; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Address <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="address" id="address" value="<?php if (isset($info->address)) echo $info->address; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Phone <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="phone" id="phone" value="<?php if (isset($info->phone)) echo $info->phone; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="email" id="email" value="<?php if (isset($info->email)) echo $info->email; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Lead Source <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="lead_source" id="lead_source" value="<?php if (isset($info->lead_source)) echo $info->lead_source; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">LinkedIn <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="linked_in" id="linked_in" value="<?php if (isset($info->linked_in)) echo $info->linked_in; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Twitter <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="twitter" id="twitter" value="<?php if (isset($info->twitter)) echo $info->twitter; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">FaceBook <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="facebook" id="facebook" value="<?php if (isset($info->facebook)) echo $info->facebook; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">FollowUp Alert <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input" name="follow_up_alert" id="follow_up_alert" value="<?php if (isset($info->follow_up_alert)) echo $info->follow_up_alert; ?>" required>
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

                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Notes <span class="text-danger">*</span></label>
                        <div class="input-container">
                            <div class="input-group control-group after-add-more col-sm-10">
                                <input type="text" name="addmore[]" class="form-control" id="addmore" >
                                <div class="input-group-btn ">
                                    <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($notes_info)) { ?>
                        <!-- <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Notes <span class="text-danger">*</span></label>
                        <div class="input-group control-group after-add-more col-sm-10">
                        <input type="hidden" id="id" name="notes_id[]" value="">
                            <input type="text" name="addmore[]" class="form-control" id="addmore" value=""  required>
                            <div class="input-group-btn ">
                                <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                            </div>
                        </div>
                    </div> -->
                    
                      <?php  foreach ($notes_info as $data) {
                            //echo $data->note
                           // if($notes_info[0]->note != $data->note) {?>
                            <div class="copy  hide_show">
                                <div class="control-group input-group" style="margin-top:10px">
                                <input type="hidden" id="id" name="notes_id[]" value="<?php if (isset($data) && $data->id) echo $data->id; ?>">
                                    <input type="text" name="addmore[]" class="form-control" value="<?php if (isset($data)) echo $data->note; ?>" id="removemore">
                                    <div class="input-group-btn">
                                        <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                    </div>
                                </div>
                            </div>
                    <?php } }
                    //}
                    ?>
                    <button type="button" class="btn butn-submit text-white sbmtBtn" id="btn">Submit</button>
                </form>
            </div>

            <div class="copy hide">
                <div class="control-group input-group" style="margin-top:10px">
                    <input type="text" name="addmore[]" class="form-control" id="removemore">
                    <div class="input-group-btn">
                        <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                    </div>
                </div>
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
        <?php if (isset($notes_info)) { ?>
            $(".hide_show").show();
            $(".hide").hide();
        <?php  } else { ?>
            $(".hide").hide();
        <?php  } ?>

        $(".add-more").click(function() {
            // var input = $('<input type="text">');
            var html = $(".copy:hidden").clone().removeClass('hide').removeAttr('style');
            $('.input-container').append(html);
            
            // // console.log(html);
            // var req_html = $(".after-add-more").after(html);
            // var inputId = $(req_html).find("input").attr("id");
            // $("#removemore").prop('required', true);
        });

        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
        });

    });

    $(document).ready(function() {
        $(".sbmtBtn").click(function(evt) {
            if ($('#lms_form').valid()) {
                $('#sbmtBtn').attr("disabled", true);
                $('#lms_form').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>