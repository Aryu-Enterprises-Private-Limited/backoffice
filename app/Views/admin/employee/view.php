<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card create-box">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <h3><?= $title; ?></h3>
                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item bread-home"><a href="<?= '/' . ADMIN_PATH . '/dashboard' ?>"><i class="fa fa-home" aria-hidden="true"></i></a></li>
                        <li class="breadcrumb-item">
                            <a href="<?= '/' . ADMIN_PATH . '/employee/list' ?>">Employee</a>
                        </li>
                        <?php if (isset($empDetails)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $empDetails->first_name; ?>
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
                            <th scope="row">First Name</th>
                            <td><?php if (isset($empDetails->first_name)) echo $empDetails->first_name; ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Last Name </th>
                            <td><?php if (isset($empDetails->last_name)) echo  ucfirst($empDetails->last_name);   ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Role </th>
                            <td><?php if (isset($roleDetails->role_name)) echo  ucfirst($roleDetails->role_name);   ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Role </th>
                            <td><?php if (isset($deptDetails->department_name)) echo  ucfirst($deptDetails->department_name);   ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> D.O.B </th>
                            <td><?php if (isset($empDetails->dob)) echo  ucfirst($empDetails->dob);   ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Phone </th>
                            <td><?php if (isset($empDetails->phone)) echo ucfirst($empDetails->phone); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Email </th>
                            <td><?php if (isset($empDetails->email)) echo ucfirst($empDetails->email); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Address </th>
                            <td><?php if (isset($empDetails->address)) echo ucfirst($empDetails->address); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Pin Code </th>
                            <td><?php if (isset($empDetails->pin_code)) echo ucfirst($empDetails->pin_code); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> City </th>
                            <td><?php if (isset($empDetails->city)) echo ucfirst($empDetails->city); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> State </th>
                            <td><?php if (isset($empDetails->state)) echo ucfirst($empDetails->state); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Blood Group </th>
                            <td><?php if (isset($empDetails->blood_grp)) echo ucfirst($empDetails->blood_grp); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Aadhar Number </th>
                            <td><?php if (isset($empDetails->aadhar_no)) echo ucfirst($empDetails->aadhar_no); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Pan Number </th>
                            <td><?php if (isset($empDetails->pan_no)) echo ucfirst($empDetails->pan_no); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Relationship </th>
                            <td><?php if (isset($empDetails->relationship)) echo ucfirst($empDetails->relationship); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Relation Name </th>
                            <td><?php if (isset($empDetails->r_name)) echo ucfirst($empDetails->r_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Relation Phone </th>
                            <td><?php if (isset($empDetails->r_phone)) echo ucfirst($empDetails->r_phone); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Relation Email </th>
                            <td><?php if (isset($empDetails->r_email)) echo ucfirst($empDetails->r_email); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Relation Address </th>
                            <td><?php if (isset($empDetails->r_address)) echo ucfirst($empDetails->r_address); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Experience of work</th>
                            <td><?php if (isset($empDetails->work_exp)) echo ucfirst($empDetails->work_exp); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Notes </th>
                            <td><?php if (isset($empDetails->notes)) echo ucfirst($empDetails->notes); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Resume </th>

                            <td><a href="<?php if (isset($empDetails->resume)) echo ('/' . ADMIN_PATH . '/employee/view_doc/' . (string)$empDetails->resume . '')  ?>" class="btn btn-info v_btn">View</td> </a>
                        </tr>
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($empDetails->status) && $empDetails->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($empDetails->created_at)) echo date('d-m-Y h:i:s A', strtotime($empDetails->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>