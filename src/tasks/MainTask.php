<?php

declare(strict_types=1);

namespace MyApp\Tasks;

use Phalcon\Cli\Task;

class MainTask extends Task
{
    public function mainAction()
    {
        // main action
    }

    public function placeOrderAction($uid, $product_id, $quantity)
    {
        // place new order here
        $sql = "INSERT INTO `orders`(`uid`, `pid`, `quantity`) VALUES ('$uid','$product_id','$quantity')";
        $result = $this->db->execute($sql);
        if ($result) {
            echo "Order Placed";
        } else {
            echo "There was some error";
        }
    }

    public function addProductAction($name, $price, $stock)
    {
        $sql = "INSERT INTO `products`(`name`, `price`, `stock`)
        VALUES ('$name','$price', '$stock')";
        $result = $this->db->execute($sql);
        if ($result) {
            echo "Product Added successfully !";
        } else {
            echo "There was some error";
        }
        echo PHP_EOL;
    }

    // method to get rating of product from users
    public function rateAction($pid, $curr_rating)
    {
        $sql = "SELECT * FROM `products` WHERE `id` = '$pid'";
        $product = $this->db->fetchAll(
            $sql,
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $rating = $product[0]['rating'];
        $rating_count = $product[0]['rating_count'];

        $new_rating = ($rating * $rating_count + $curr_rating) / ($rating_count + 1);
        $rating_count++;


        $updateQ = "UPDATE `products` SET `rating`= '$new_rating',`rating_count`= '$rating_count' WHERE `id` = '$pid'";

        $result = $this->db->execute($updateQ);
        if ($result) {
            echo "Thank you for rating the product!";
        } else {
            echo "There was some error in updating the rating";
        }
        echo PHP_EOL;
    }

    // method to display all products according to their rating
    public function displayAction()
    {
        $sql = "SELECT * FROM `products` ORDER BY `rating`";
        $products = $this->db->fetchAll(
            $sql,
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        foreach ($products as $product) {
            echo "Name : " . $product['name'] . PHP_EOL;
            echo "Price : " . $product['price'] . PHP_EOL;
            echo "Rating : " . $product['rating'] . PHP_EOL;
            echo PHP_EOL;
        }
    }
}
