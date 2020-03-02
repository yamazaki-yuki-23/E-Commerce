<?php

namespace Shopping;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $items; // ['id' => ['quantity' => , 'price' => , 'data' =>],,,,]
    public $totalQuantity;
    public $totalPrice;



    public function __construct($prevCart) {
        if($prevCart != null) {
            $this->items = $prevCart->items;
            $this->totalQuantity = $prevCart->totalQuantity;
            $this->totalPrice = $prevCart->totalPrice;

        }else{
            $this->items = [];
            $this->totalQuantity = 0;
            $this->totalPrice = 0;

        }
    }

    public function addItem($id, $product) {

        $price = (int) str_replace("$","",$product->price);

        //the item already exists
        if(array_key_exists($id,$this->items)){

            $productToAdd = $this->items[$id];
            $productToAdd['quantity']++;
            $productToAdd['totalSinglePrice'] = $productToAdd['quantity'] *  $price;


            //first time to add this product to cart
        }else{

            $productToAdd = ['quantity'=> 1, 'totalSinglePrice'=> $price, 'data'=>$product];


        }

        $this->items[$id] = $productToAdd;
        $this->totalQuantity++;
        $this->totalPrice = $this->totalPrice + $price;

    }

    public function updatePriceAndQuantity() {

        $totalPrice = 0;
        $totalQuantity = 0;

        foreach($this->items as $item) {

            $totalQuantity = $totalQuantity + $item['quantity'];
            $totalPrice = $totalPrice + $item['totalSinglePrice'];

        }

        $this->totalQuantity = $totalQuantity;
        $this->totalPrice = $totalPrice;

    }
}
