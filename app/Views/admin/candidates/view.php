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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/candidates/list' ?>">Candidate</a>
                        </li>
                        <?php if (isset($candidatesDetails)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $candidatesDetails->first_name; ?>
                            </li>
                        <?php } ?>
                        <li class="breadcrumb-item active">
                            <?php echo 'view'; ?>
                        </li>
                    </ol>
                    <hr>
                    <h3><?= $title; ?></h3>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table table-bordered m-b-0">
                    <tbody>
                        <tr>
                            <th scope="row"> Date </th>
                            <td><?php if (isset($candidatesDetails->date)) echo $candidatesDetails->date; ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Name </th>
                            <td><?php if (isset($candidatesDetails->first_name)) echo ucfirst($candidatesDetails->first_name) . ' ' . ucfirst($candidatesDetails->first_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Location </th>
                            <td><?php if (isset($candidatesDetails->location)) echo  ucfirst($candidatesDetails->location);   ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Resume </th>
                            <?php if (isset($candidatesDetails->resume) && $candidatesDetails->resume != '') { ?>
                                <td><a href="<?php if (isset($candidatesDetails->resume) && $candidatesDetails->resume != '') echo ('/' . ADMIN_PATH . '/candidates/view_doc/' . (string)$candidatesDetails->resume . '')  ?>" class="btn btn-info v_btn">View</td> </a>
                            <?php  } else { ?>
                                <td><a class="btn v_btn">No Document </a></td>
                            <?php } ?>

                        </tr>
                        <tr>
                            <th scope="row"> Contact Number </th>
                            <td><?php if (isset($candidatesDetails->contact_no)) echo ucfirst($candidatesDetails->contact_no); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Email </th>
                            <td><?php if (isset($candidatesDetails->email)) echo ucfirst($candidatesDetails->email); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Job Opening </th>
                            <td><?php if (isset($job_details->jobs_name)) echo ucfirst($job_details->jobs_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Application Status </th>
                            <td><?php if (isset($app_details->app_status)) echo ucfirst($app_details->app_status); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Interview Status </th>
                            <td><?php if (isset($int_sts_details->interview_sts)) echo ucfirst($int_sts_details->interview_sts); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Stage </th>
                            <td><?php if (isset($stage_details->stage_name)) echo ucfirst($stage_details->stage_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Stage </th>
                            <td><?php if (isset($stage_details->stage_name)) echo ucfirst($stage_details->stage_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Backgound Check </th>
                            <td><?php if (isset($candidatesDetails->background_check) && $candidatesDetails->background_check == '0') echo 'NO';
                                else echo 'YES'; ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Source </th>
                            <td><?php if (isset($candidatesDetails->source)) echo ucfirst($candidatesDetails->source); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Reason for Rejection </th>
                            <td><?php if (isset($rr_details->reason_for_rej)) echo ucfirst($rr_details->reason_for_rej); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Reason </th>
                            <td><?php if (isset($candidatesDetails->reason)) echo ucfirst($candidatesDetails->reason); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($candidatesDetails->status) && $candidatesDetails->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($candidatesDetails->created_at)) echo date('d-m-Y h:i:s A', strtotime($candidatesDetails->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>