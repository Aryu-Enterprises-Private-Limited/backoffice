<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <h3><?= $title; ?></h3>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn  butn-back text-white">Back</button>
                </div>

            </div>


            <div class="table-responsive">
                <table class="table table-bordered m-b-0">
                    <tbody>
                        <tr>
                            <th scope="row"> Lead </th>
                            <td><?php if (isset($lmsDetails->first_name)) echo $lmsDetails->first_name . ' ' . $lmsDetails->last_name; ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Project Details </th>
                            <td><?php if (isset($crmDetails->project_details)) echo  ucfirst($crmDetails->project_details);   ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Price </th>
                            <td><?php if (isset($crmDetails->price)) echo ucfirst($crmDetails->price); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Document </th>
                            <td><a href="<?php if (isset($crmDetails->resume)) echo ('/' . ADMIN_PATH . '/crm/view_doc/' . (string)$crmDetails->document_name . '')  ?>" class="btn btn-info v_btn">View</td> </a>
                        </tr>

                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($crmDetails->status) && $crmDetails->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($crmDetails->created_at)) echo date('d-m-Y h:i:s A', strtotime($crmDetails->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>