<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title> <?= $title; ?> </title>
</head>

<body>
    <div class="conatiner">
        <div class="aryu-logo">
            <img src="images/aryulogo.png" class="rounded" alt="...">
        </div>
        <div class="card total-card">
            <img src="images/aryulogo_boder.png" class="rounded top-border" alt="...">
            <div class="card-body">
                <div class="card log-card">
                    <div class="log">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="images/login.png" class="rounded log-key" alt="...">
                            </div>
                            <div class="col-md-10">
                                <h2 class="admin"><?= $title; ?></h2>
                                <?= isset($error) ? $error : '' ?>
                            </div>
                        </div>
                    </div>

                    <div class="mail-pass ">
                        <form id="login_form" method="post" action="<?= (base_url(EMPLOYEE_PATH . '/do-login'))  ?>" autocomplete="off">
                            <div class="mb-3 inputicon">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <i class="fa fa-envelope favicon-login" aria-hidden="true"></i>
                                <input type="email" class="form-control input-login" id="email" placeholder="Email" name="email" required>
                            </div>
                            <div class="mb-3 inputicon">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <i class="fa fa-lock favicon-password" aria-hidden="true"></i>
                                <input type="password" class="form-control input-login" id="password" placeholder="Password" name="password" required>
                            </div>

                            <div class="button">
                                <button type="submit" id="sbmtBtn" class="btn login-button text-white">Login</button>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Remember me
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <p>Forgot Password?</p>
                                </div>
                                <div class="row">
                                    <p class="text-center">Need An Account?<a href="<?= (base_url(EMPLOYEE_PATH . '/register'))  ?>">Register</a></p>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>


    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include jQuery Validation plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <?php if (session('success_message')) : ?>
        <script>
            toastr.success('<?= session('success_message') ?>');
        </script>
    <?php endif; ?>
    <?php if (session('error_message')) : ?>
        <script>
            toastr.error('<?= session('error_message') ?>');
        </script>
    <?php endif; ?>
    <script>
        $(document).ready(function() {
            $("#login_form").validate({
                rules: {
                    // username: {
                    //     required: true,
                    //     minlength: 3
                    // },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 8
                    }

                },
                messages: {
                    password: {
                        required: "Please enter a password",
                        minlength: "password must be at least 8 characters long"
                    },
                    email: {
                        required: "Please enter an email address",
                        email: "Please enter a valid email address",
                    }
                }
            });
        });
    </script>



</body>

</html>