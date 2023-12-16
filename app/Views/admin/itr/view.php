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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/itr/list' ?>">ITR</a>
                        </li>
                        <?php if (isset($itr_details)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $itr_details->assessment_yr; ?>
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
                            <th scope="row"> Assessment year </th>
                            <td><?php if (isset($itr_details->assessment_yr)) echo ucfirst($itr_details->assessment_yr); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Finance year </th>
                            <td><?php if (isset($itr_details->finance_yr)) echo ucfirst($itr_details->finance_yr); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Filed Date </th>
                            <td><?php if (isset($itr_details->filed_date)) echo ucfirst($itr_details->filed_date); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Paid Date </th>
                            <td><?php if (isset($itr_details->paid_date)) echo ucfirst($itr_details->paid_date); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Taxable Amount </th>
                            <td><?php if (isset($itr_details->tax_amount)) echo ucfirst($itr_details->tax_amount); ?></td>
                        </tr>
                        <?php 
                        $commaSeparated = $itr_details->itr_document;
                        $fileNamesArray = explode(',', $commaSeparated);
                        foreach($fileNamesArray as $key => $file){
                        ?>
                        <tr>
                            <th scope="row"> Document <?= $key +1 ; ?></th>
                            <?php if (isset($file) && $file != '') { ?>
                                <td><a target="_blank" href="<?php if (isset($file)) echo ('/' . ADMIN_PATH . '/itr/view_doc/' . (string)$file . '')  ?>" class="btn btn-info v_btn">View</td> </a>
                            <?php  } else { ?>
                                <td><a class="btn v_btn">No Document </a></td>
                            <?php } ?>
                        </tr>
                   <?php } ?>
                        <!-- <tr>
                            <th scope="row"> Document </th>
                            <?php if (isset($itr_details->itr_document) && $itr_details->itr_document != '') { ?>
                                <td><a href="<?php if (isset($itr_details->itr_document)) echo ('/' . ADMIN_PATH . '/itr/view_doc/' . (string)$itr_details->itr_document . '')  ?>" class="btn btn-info v_btn">View</td> </a>
                            <?php  } else { ?>
                                <td><a class="btn v_btn">No Document </a></td>
                            <?php } ?>
                        </tr> -->
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($itr_details->status) && $itr_details->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($itr_details->created_at)) echo date('d-m-Y h:i:s A', strtotime($itr_details->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>