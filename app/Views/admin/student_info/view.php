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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/student_info/list' ?>">Student Info</a>
                        </li>
                        <?php if (isset($stuDetails)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $stuDetails->first_name; ?>
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
                            <th scope="row"> Name </th>
                            <td><?php if (isset($stuDetails->first_name)) echo ucfirst($stuDetails->first_name) .' '. ucfirst($stuDetails->last_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Email </th>
                            <td><?php if (isset($stuDetails->email)) echo ucfirst($stuDetails->email); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Current Status </th>
                            <td><?php if (isset($stuDetails->current_status)) echo ucfirst(str_replace("_", " ", $stuDetails->current_status)) ; ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> College/Company information </th>
                            <td><?php if (isset($stuDetails->clg_comp_info)) echo ucfirst($stuDetails->clg_comp_info); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Parent / Guardian Name </th>
                            <td><?php if (isset($stuDetails->par_guard_name)) echo ucfirst($stuDetails->par_guard_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Parent / Guardian Phone </th>
                            <td><?php if (isset($stuDetails->par_guard_phno)) echo ucfirst($stuDetails->par_guard_phno); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Parent Occupation </th>
                            <td><?php if (isset($stuDetails->parent_occ) && $stuDetails->parent_occ !='') echo ucfirst($stuDetails->parent_occ); else echo '-'; ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Reference Number 1 </th>
                            <td><?php if (isset($stuDetails->ref_no_1) && $stuDetails->ref_no_1 !='') echo ucfirst($stuDetails->ref_no_1); else echo '-'; ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Reference Number 2 </th>
                            <td><?php if (isset($stuDetails->ref_no_2) && $stuDetails->ref_no_2 !='') echo ucfirst($stuDetails->ref_no_2); else echo '-'; ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($stuDetails->status) && $stuDetails->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($stuDetails->created_at)) echo date('d-m-Y h:i:s A', strtotime($stuDetails->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>