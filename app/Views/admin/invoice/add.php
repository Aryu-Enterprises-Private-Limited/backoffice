<!-- Start content -->
<?= $this->extend('layout') ?>
<?php //echo"<pre>";print_r($pdfString);die; 
?>
<?= $this->section('content') ?>
<?php if (isset($pdfString)) {  
    $req = ''
    
    ?>

<?php } else{
    $req = 'required';
} ?>

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
                <form id="client_form" method="post" action="<?= (base_url(ADMIN_PATH . '/invoice/gen_invoice'))  ?>" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($client_info) && $client_info->id) echo $client_info->id; ?>">
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold">From <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select name="from_name" class="form-control create-input" id="from_name" required>
                                <option value=""> select</option>
                                <?php foreach ($client_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($form_data['from_name']) && $form_data['from_name'] == ucfirst($value->first_name) . ' ' . ucfirst($value->last_name)) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo ucfirst($value->first_name) . ' ' . ucfirst($value->last_name); ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->first_name); ?> <?php echo ucfirst($value->last_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row ">
                        <label class="col-sm-2 col-form-label fw-bold"> To <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select name="to_name" class="form-control create-input" id="to_name" required>
                                <option value=""> select</option>
                                <?php foreach ($client_opt as $key => $value) {
                                    $selected = '';
                                    if (isset($form_data['to_name']) && $form_data['to_name'] == ucfirst($value->first_name) . ' ' . ucfirst($value->last_name)) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?php echo ucfirst($value->first_name) . ' ' . ucfirst($value->last_name); ?>" <?= $selected; ?>>
                                        <?php echo ucfirst($value->first_name); ?> <?php echo ucfirst($value->last_name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold">Description <span class="text-danger">*</span></label>
                        <div class="col-sm-10 input-container">
                            <div class="input-group input-groups control-group after-add-more col-sm-10">
                                <input type="text" name="addmore[]" class="form-control" id="addmore" placeholder="Description" value="" <?= $req ;?>>
                                <div class="input-group-btn">
                                    <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (isset($form_data['addmore'])) {
                        foreach ($form_data['addmore'] as $data) {
                            if ($data != '') {
                    ?>
                                <div class="mb-3 row copy hide_show">
                                    <div class="col-sm-10 ">
                                        <div class="control-group input-group input-grp removemore" style="margin-top:10px">
                                            <input type="text" name="addmore[]" class="form-control" value="<?php if (isset($data)) echo $data; ?>">
                                            <div class="input-group-btn">
                                                <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                    <?php }
                    } ?>
                    <div class="mb-3 row copy hide">
                        <div class="col-sm-10 ">
                            <div class="control-group input-group input-grp removemore" style="margin-top:10px">
                                <input type="text" name="addmore[]" class="form-control">
                                <div class="input-group-btn">
                                    <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Amount <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <div class="input-group input-groups control-group after-add-more1 col-sm-10">
                                <input type="text" name="amntmore[]" class="form-control" placeholder="Amount" <?= $req ;?>>
                                <div class="input-group-btn">
                                    <button class="btn btn-success add-more1" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (isset($form_data['amntmore'])) {
                        foreach ($form_data['amntmore'] as $data) {
                            if ($data != '') {
                    ?>
                                <div class="mb-3 row copy  hide_show">
                                    <div class="col-sm-10 ">
                                        <div class="control-group input-group input-grp removemore" style="margin-top:10px">
                                            <input type="text" name="amntmore[]" class="form-control" value="<?php if (isset($data)) echo $data; ?>">
                                            <div class="input-group-btn">
                                                <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                    <?php }
                        }
                    } ?>
                    <div class="mb-3 row copy1 hide">
                        <div class="col-sm-10 ">
                            <div class="control-group input-group input-grp removemore" style="margin-top:10px">
                                <input type="text" name="amntmore[]" class="form-control">
                                <div class="input-group-btn">
                                    <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Quantity <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <div class="input-group input-groups control-group after-add-more2 col-sm-10">
                                <input type="text" name="qntymore[]" class="form-control" placeholder="Quantity" <?= $req ;?>>
                                <div class="input-group-btn">
                                    <button class="btn btn-success add-more2" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (isset($form_data['qntymore'])) {
                        foreach ($form_data['qntymore'] as $data) {
                            if ($data != '') {
                    ?>
                                <div class="mb-3 row copy  hide_show">
                                    <div class="col-sm-10 ">
                                        <div class="control-group input-group input-grp removemore" style="margin-top:10px">
                                            <input type="text" name="qntymore[]" class="form-control" value="<?php if (isset($data)) echo $data; ?>">
                                            <div class="input-group-btn">
                                                <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                    <?php }
                        }
                    } ?>
                    <div class="mb-3 row copy2 hide">
                        <div class="col-sm-10 ">
                            <div class="control-group input-group input-grp removemore" style="margin-top:10px">
                                <input type="text" name="qntymore[]" class="form-control">
                                <div class="input-group-btn">
                                    <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Amount <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input after-add-more" name="amountmore[]" placeholder ="Amount" required>
                            <div class="input-group-btn">
                                    <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                                </div>
                        </div>
                    </div>
                    <div class="mb-3 row copy hide">
                        <div class="col-sm-10 ">
                            <div class="control-group input-group input-grp removemore" style="margin-top:10px">
                                <input type="text" name="amountmore[]" class="form-control">
                                <div class="input-group-btn">
                                    <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Quantity <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control create-input after-add-more" name="qntymore[]"  placeholder ="Quantity" required>
                        </div>
                    </div> -->
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Invoice No <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <?php 
                            $date = date('d');
                            $month = date('m');
                            $year = date('Y');
                            $id = 1;
                            if(isset($invoice_id->id) && $invoice_id->id != ''){
                                $id = $invoice_id->id +1;
                            }
                            
                            $form_data['invoice_no'] = 'AYE'.$month.$date.$year.'0'.$id;
                            ?>
                            <input type="text" readonly class="form-control create-input" name="invoice_no" id="invoice_no" value="<?php if (isset($form_data['invoice_no'])) echo $form_data['invoice_no']; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-bold"> Date <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control create-input" name="invoice_date" id="invoice_date" value="<?php if (isset($form_data['invoice_date'])) echo $form_data['invoice_date']; ?>" required>
                        </div>
                    </div>
                    <button type="button" class="btn butn-submit text-white sbmtBtn" id="btn">Preview</button>
                    <!-- <a type="button" class="btn butn-submit text-white sbmtBtn" id="btn">Preview</a> -->
                </form>
            </div>
            <?php if (isset($pdfString)) { ?>
                <iframe src="data:application/pdf;base64,<?= base64_encode($pdfString) ?>" type="application/pdf" width="100%" height="1000px"></iframe>
            <?php } ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<?php if (session('error_message')) : ?>
    <script>
        toastr.error('<?= session('error_message') ?>');
    </script>
<?php endif; ?>
<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($form_data['pdf']) && $form_data['pdf'] == 1) { ?>
            $(".hide_show").show();
            $(".hide").hide();
        <?php  } else { ?>
            $(".hide").hide();
        <?php  } ?>


        $(".add-more").click(function() {
            // var html = $(".copy").html();
            // $(".after-add-more").after(html);

            var html = $(".copy:hidden").clone().removeClass('hide').removeAttr('style');
            $('.input-container').append(html);
        });

        $(".add-more1").click(function() {
            var html = $(".copy1").html();
            $(".after-add-more1").after(html);
        });

        $(".add-more2").click(function() {
            var html = $(".copy2").html();
            $(".after-add-more2").after(html);
        });


        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
            //   $(".removemore").remove();
        });

        $(".sbmtBtn").click(function(evt) {
            if ($('#client_form').valid()) {
                $('#sbmtBtn').attr("disabled", true);
                $('#client_form').submit();
            }
        });

    });
</script>
<?= $this->endSection() ?>