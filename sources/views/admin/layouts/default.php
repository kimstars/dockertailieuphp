<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>
        CFS3 | <?php defineblock('title')?>
    </title>
    <link rel="shortcut icon" href="<?= asset('images/logo.png') ?>" />
    <link href="<?=asset('admin/vendor/fontawesome-free/css/all.min.css')?>" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="<?=asset('admin/css/style.min.css')?>" rel="stylesheet">
    <link href="<?=asset('admin/css/toastr.min.css')?>" type="text/css" rel="stylesheet">
    <?php defineblock('link')?>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include_once 'views/admin/layouts/includes/_sidebar.php'?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include_once 'views/admin/layouts/includes/_topbar.php' ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php defineblock('content')?>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; THPT HA HOA CFS3 <?=date("Y")?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sẵn sàng thoát?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Chọn đăng xuất nếu bạn sẵn sàng thoát phiên đăng nhập</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Thoát</button>
                    <a class="btn btn-primary" href="<?=url('admin/auth/logout')?>">Đăng xuất</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?=asset('admin/vendor/jquery/jquery.min.js')?>"></script>
    <script src="<?=asset('admin/js/toastr.min.js')?>"></script>
    <script src="<?=asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
    <script src="<?=asset('admin/vendor/jquery-easing/jquery.easing.min.js')?>"></script>
    <!-- Custom scripts for all pages-->
    <script src="<?=asset('admin/js/app.min.js')?>"></script>
    <script>
        <?php if (Flash::has('success')): ?>
            toastr.success('<?=Flash::get('success')?>', 'Thông báo!')
        <?php endif?>
        <?php if (Flash::has('error')): ?>
            toastr.error('<?=Flash::get('error')?>', 'Thông báo!')
        <?php endif?>
    </script>
    <?php defineblock('script')?>
</body>

</html>