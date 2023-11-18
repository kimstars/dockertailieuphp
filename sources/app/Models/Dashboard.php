<?php 

require_once('app/Models/Model.php');

class Dashboard extends Model
{

    public function sellings()
    {
        $sql = "SELECT order_details.product_id as 'id', MIN(products.name) as 'name', MIN(products.price) as 'price', MIN(products.thumbnail) as 'thumbnail' , SUM(order_details.quantity) as 'quantity' FROM `order_details` INNER JOIN products ON products.id = order_details.product_id GROUP BY order_details.product_id ORDER BY quantity DESC LIMIT 8";
        return $this->getAll($sql);
    }

    public function countProduct()
    {
        $sql = "SELECT count(id) as 'count' FROM `products`";
        return $this->getFirst($sql)['count'];
    }

    public function countCategory()
    {
        $sql = "SELECT count(id) as 'count' FROM `categories`";
        return $this->getFirst($sql)['count'];
    }

    public function countOrder1M()
    {
        $sql = "SELECT count(id) as 'count' FROM orders WHERE created_at > now() - interval 1 month;";
        return $this->getFirst($sql)['count'];
    }

    public function totalOrder1M()
    {
        $sql = "SELECT sum(orders.total) as 'sum' FROM orders WHERE created_at > now() - interval 1 month";
        return $this->getFirst($sql)['sum'];

    }

}
