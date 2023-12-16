<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <h3><?= $title; ?></h3>
                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item bread-home"><a href="<?= '/' . ADMIN_PATH . '/dashboard' ?>"><i class="fa fa-home me-0" aria-hidden="true"></i></a></li>
                        <li class="breadcrumb-item">
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/tds/list' ?>">ITR</a>
                        </li>
                        <?php if (isset($tds_details)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $tds_details->year; ?>
                            </li>
                        <?php } ?>
                        <li class="breadcrumb-item active">
                            <?php echo 'view'; ?>
                        </li>
                    </ol>
                    <hr>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered m-b-0">
                    <tbody>
                        <tr>
                            <th scope="row"> year </th>
                            <td><?php if (isset($tds_details->year)) echo ucfirst($tds_details->year); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Type </th>
                            <td><?php if (isset($tds_details->type)) echo ucfirst($tds_details->type); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Filed Date </th>
                            <td><?php if (isset($tds_details->filed_date)) echo ucfirst($tds_details->filed_date); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Paid Date </th>
                            <td><?php if (isset($tds_details->paid_date)) echo ucfirst($tds_details->paid_date); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Amount </th>
                            <td><?php if (isset($tds_details->amount)) echo ucfirst($tds_details->amount); ?></td>
                        </tr>
                        <?php 
                        $commaSeparated = $gst_details->tds_document;
                        $fileNamesArray = explode(',', $commaSeparated);
                        foreach($fileNamesArray as $key => $file){
                        ?>
                        <tr>
                            <th scope="row"> Document <?= $key +1 ; ?></th>
                            <?php if (isset($file) && $file != '') { ?>
                                <td><a target="_blank" href="<?php if (isset($file)) echo ('/' . ADMIN_PATH . '/tds/view_doc/' . (string)$file . '')  ?>" class="btn btn-info v_btn">View</td> </a>
                            <?php  } else { ?>
                                <td><a class="btn v_btn">No Document </a></td>
                            <?php } ?>
                        </tr>
                   <?php } ?>
                        <!-- <tr>
                            <th scope="row"> Document </th>
                            <?php if (isset($tds_details->tds_document) && $tds_details->tds_document != '') { ?>
                                <td><a href="<?php if (isset($tds_details->tds_document)) echo ('/' . ADMIN_PATH . '/tds/view_doc/' . (string)$tds_details->tds_document . '')  ?>" class="btn btn-info v_btn">View</td> </a>
                            <?php  } else { ?>
                                <td><a class="btn v_btn">No Document </a></td>
                            <?php } ?>
                        </tr> -->
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($tds_details->status) && $tds_details->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($tds_details->created_at)) echo date('d-m-Y h:i:s A', strtotime($tds_details->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>