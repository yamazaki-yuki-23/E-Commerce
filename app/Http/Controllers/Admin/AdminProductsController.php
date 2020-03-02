<?php

namespace Shopping\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Shopping\Http\Controllers\Controller;
use Shopping\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdminProductsController extends Controller
{
    //display all products
    public function index() {
        $products = Product::paginate(3);
        return view("admin.displayProducts", ['products' => $products]);
    }


    public function createProductForm() {
        return view('admin.createProductForm');
    }


    //send new product to database
    public function sendCreateProductForm(Request $request) {

        $name = $request->input('name');
        $description =  $request->input('description');
        $type = $request->input('type');
        $price =  $request->input('price');

        Validator::make($request->all(), ['image' => "required|file|image|mimes:jpg,png,jpeg|max:5000"])->validate();
        $ext = $request->file('image')->getClientOriginalExtension(); //jpg
        $stringImageReFormat = str_replace(" ", "", $request->input('name'));


        $imageName = $stringImageReFormat.".".$ext; //blackdress.jpg
        $imageEncoded =  File::get($request->image);
        Storage::disk('local')->put('public/product_images/'.$imageName, $imageEncoded);


        $newProductArray = array("name" => $name, "description" => $description, "image" => $imageName,  "type" => $type, "price" => $price);

        $created = DB::table('products')->insert($newProductArray);

        if($created) {
            return redirect()->route("adminDisplayProducts");
        }else{
            return "Product was not Created";
        }

    }


    //display edit product form
    public function editProductForm($id) {
        $product = Product::find($id);
        return view('admin.editProductForm', ['product' => $product]);

    }


    //display edit product image form
    public function editProductImageForm($id) {
        $product = Product::find($id);
        return view('admin.editProductImageForm', ['product' => $product]);
    }


    public function updateProductImage(Request $request, $id) {

        Validator::make($request->all(), ['image' => "required|file|image|mimes:jpg,png,jpeg|max:5000"])->validate();

        if($request->hasFile("image")){
            $product = Product::find($id);
            $exists =  Storage::disk('local')->exists("public/product_images/".$product->image);

            //delete old image
            if($exists) {
                //delete it
                Storage::delete('public/product_images/'.$product->image);
            }

            //upload nav image
            $ext = $request->file('image')->getClientOriginalExtension(); //jpg

            $request->image->storeAs("public/product_images/", $product->image);

            $arrayToUpdate = array('image' => $product->image);
            DB::table('products')->where('id', $id)->update($arrayToUpdate);


            return redirect()->route("adminDisplayProducts");

        }else{

            $error = "NO Image was Selected";
            return $error;



        }


    }


    public function updateProduct(Request $request, $id) {

        $name = $request->input('name');
        $description =  $request->input('description');
        $type = $request->input('type');
        $price =  $request->input('price');

        $updateArray = array("name" => $name, "description" => $description, "type" => $type, "price" => $price);

        DB::table('products')->where('id', $id)->update($updateArray);

        return redirect()->route("adminDisplayProducts");


    }



    public function deleteProduct($id) {

        $product = Product::find($id);

        $exists =  Storage::disk('local')->exists("public/product_images/".$product->image);

        //delete old image
        if($exists) {
            //delete it
            Storage::delete('public/product_images/'.$product->image);
        }


        Product::destroy($id);

        return redirect()->route("adminDisplayProducts");
    }


    //orders control panel [display all orders]
    public function ordersPanel() {

        $orders = DB::table('orders')->paginate(10);
        return view('admin.ordersPanel', ["orders" => $orders]);
    }



    public function deleteOrder(Request $request, $id) {

        $deleted = DB::table('orders')->where("order_id",$id)->delete();

        if($deleted) {
            return redirect()->back()->with('orderDeletionStatus', 'Order '.$id.'was successfuly deleted');
        }else{
            return redirect()->back()->with('orderDeletionStatus', 'Order '.$id. 'was NOT deleted');

        }


    }


    //display edit order form
    public function editOrderForm($order_id) {

        $order = DB::table('orders')->where('order_id', $order_id)->get();

        return view('admin.editOrderForm', ['order' => $order[0]]);
    }



    //update order fields (status,date,,,,,,,)
    public function updateOrder(Request $request, $order_id) {

        $date = $request->input('date');
        $del_date = $request->input('del_date');
        $status = $request->input('status');
        $price = $request->input('price');

        $updateArray = array("date"=>$date, "del_date"=>$del_date, "status"=>$status, "price"=>$price);

        DB::table('orders')->where('order_id', $order_id)->update($updateArray);

        return redirect()->route("ordersPanel");
    }


}
