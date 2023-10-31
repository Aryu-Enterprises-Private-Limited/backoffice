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
                            <a class="text-decoration-none" href="<?= '/' . ADMIN_PATH . '/links/list' ?>">Links</a>
                        </li>
                        <?php if (isset($links_Details)) { ?>
                            <li class="breadcrumb-item">
                                <?php echo $links_Details->department_name; ?>
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
                            <th scope="row"> Department Name </th>
                            <td><?php if (isset($links_Details->department_name)) echo ucfirst($links_Details->department_name); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Links Tags </th>
                            <td><?php if (isset($links_Details->links_tags)) echo ucfirst($links_Details->links_tags); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Link </th>
                            <td><?php if (isset($links_Details->link)) echo ucfirst($links_Details->link); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($links_Details->status) && $links_Details->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($links_Details->created_at)) echo date('d-m-Y h:i:s A', strtotime($links_Details->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>