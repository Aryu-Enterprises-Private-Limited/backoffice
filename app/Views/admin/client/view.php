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
                            <th scope="row"> Name </th>
                            <td><?php if (isset($clientDetails->first_name)) echo $clientDetails->first_name . ' ' . $clientDetails->last_name; ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Email </th>
                            <td><?php if (isset($clientDetails->email)) echo  ucfirst($clientDetails->email);   ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Phone </th>
                            <td><?php if (isset($clientDetails->phone)) echo ucfirst($clientDetails->phone); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Address </th>
                            <td><?php if (isset($clientDetails->address)) echo ucfirst($clientDetails->address); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Status </th>
                            <td><?php if (isset($clientDetails->status) && $clientDetails->status == 1) { ?><span class="btn btn-success"><?php echo 'Active'; ?></span><?php } else { ?><span class="btn btn-danger"><?php echo 'In Active'; ?></span><?php } ?></td>
                        </tr>
                        <tr>
                            <th scope="row"> Created At </th>
                            <td><?php if (isset($clientDetails->created_at)) echo date('d-m-Y h:i:s A', strtotime($clientDetails->created_at)); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection() ?>