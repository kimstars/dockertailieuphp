<?php
require_once('app/Models/Model.php');

class CartService
{
    public function addToCart($productId, $quantity = 1)
    {
        if (isset($_SESSION['cart'][$productId])) {
            $quantity = $_SESSION['cart'][$productId]['quantity'] + $quantity;
            return $_SESSION['cart'][$productId]['quantity'] = $quantity;
        } else {
            return $_SESSION['cart'][$productId]['quantity'] = $quantity;
        }
    }

    public function updateCart($productId, $quantity = 1)
    {
        if (isset($_SESSION['cart'][$productId])) {
            if ($quantity > 0) {
                $_SESSION['cart'][$productId]['quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$productId]);
                Flash::set('success', 'Xoá sản phẩm khỏi giỏ hàng thành công!');
            }
        }
    }

    public function viewCart()
    {
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $model = new Model();
            require_once 'config/color.php';
            $cart_keys = array_keys($_SESSION['cart']);
            foreach ($cart_keys as $cart_key) {
                $id = $cart_key;
                $color = [
                    'label' => 'Mặc định'
                ];
                if (str_contains($cart_key, '_')) {
                    $id = explode('_', $cart_key)[0];
                    $color = getColor(explode('_', $cart_key)[1]);
                }
                $sql = "SELECT * FROM products WHERE id = $id";
                $product = $model->getFirst($sql);
                $_SESSION['cart'][$cart_key]['product-detail'] = $product;
                $_SESSION['cart'][$cart_key]['color'] = $color;
            }
            return $_SESSION['cart'];
        } else {
            return [];
        }
    }

    public function deleteCart() {
        unset($_SESSION['cart']);
    }
}
