<?php 
    require_once('views/admin/layouts/default.php');
?>
<?php startblock('title') ?>
Thống kê
<?php endblock() ?>

<?php startblock('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div
                        class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">weekend</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Tổng số danh mục</p>
                        <h4 class="mb-0"><?= $data['countCategory'] ?></h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div
                        class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">person</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Tổng số sản phẩm</p>
                        <h4 class="mb-0"><?= $data['countProduct'] ?></h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div
                        class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">person</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Đơn hàng trong 30 ngày</p>
                        <h4 class="mb-0"><?= $data['countOrder1M'] ?></h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div
                        class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">weekend</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Doanh thu trong 30 ngày</p>
                        <h4 class="mb-0"><?= number_format($data['totalOrder1M']) ?>đ</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Thống kê sản phẩm bán chạy</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Tên sản phẩm</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ảnh
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Doanh thu</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Số lượng bán được</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($sellings as $selling): ?>
                                <tr>
                                    <td style="padding-left: 20px">
                                        <?= $selling['id'] ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $selling['name'] ?>
                                    </td>
                                    <td>
                                        <img onclick="openModal('#product_image_<?=$selling['id'] ?>')"
                                            src="<?= asset('storage/products/'.$selling['thumbnail']) ?>"
                                            class="img-thumbnail" style="width: 50px">
                                        <div class="modal fade" id="product_image_<?=$selling['id'] ?>" tabindex="-1"
                                            role="dialog">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Thumbnail</h4>
                                                        <button type="button" class="close"
                                                            onclick="closeModal('#product_image_<?=$selling['id'] ?>')">
                                                            ×
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img src="<?= asset('storage/products/'.$selling['thumbnail']) ?>"
                                                            class="img-thumbnail">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?= number_format($selling['price']*$selling['quantity']) ?>đ
                                    </td>
                                    <td class="text-center">
                                        <?= $selling['quantity'] ?>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endblock() ?>

<?php startblock('script') ?>
<script>
    function changeStatus(e) {
        let id = e.getAttribute('data-id')
        let active = 1 - e.getAttribute('data-active')
        window.location.href = `<?= url('admin/product/status') ?>?id=${id}&active=${active}`
    }
</script>
<?php endblock() ?>