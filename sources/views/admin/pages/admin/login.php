<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <base href="<?php echo BASE_PATH ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?=asset('web/images/favicon.webp')?>" type="image/png" />
    <title>
        Đăng nhập quản trị viên
    </title>
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="<?=asset('admin/css/style.min.css')?>" rel="stylesheet">
    <link href="<?=asset('admin/css/toastr.min.css')?>" type="text/css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Chào mừng quay trở lại!</h1>
                                        <span class="text-danger"><?=isset($errors['wrong_email_password']) ? $errors['wrong_email_password'] : ''?></span>
                                    </div>
                                    <form class="user" method="post" action="<?=url('admin/auth/handleLogin')?>">
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-user" placeholder="Nhập email" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" name="rememberMe" id="rememberMe" class="custom-control-input" >
                                                <label class="custom-control-label" for="rememberMe">Remember Me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Đăng nhập
                                        </button>
                                        <hr>
                                        <!-- <a href="index.html" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a> -->
                                    </form>
                                    <!-- <hr> -->
                                    <!-- <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.html">Create an Account!</a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="<?=asset('admin/vendor/jquery/jquery.min.js')?>"></script>
    <script src="<?=asset('admin/js/toastr.min.js')?>"></script>
    <script>
        <?php if (Flash::has('success')): ?>
            toastr.success('<?=Flash::get('success')?>', 'Thông báo!')
        <?php endif?>
        <?php if (Flash::has('error')): ?>
            toastr.error('<?=Flash::get('error')?>', 'Thông báo!')
        <?php endif?>
    </script>
</body>
</html>