<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Session;
use Cart;
use Illuminate\Support\Facades\Redirect;
session_start();
class CartController extends Controller
{
    public function save_cart(Request $request){
    	

    	$productId = $request->productid_hidden;
    	$quantity = $request->qty;
    	$product_info = DB::table('san_pham')->where('maSP',$productId)->first();
        $data['id'] = $productId;
    	$data['name'] = $product_info->tenSP;
    	$data['price'] = $product_info->giaSP;
    	$data['qty'] = $quantity;
    	$data['weight'] = $product_info->giaSP;
    	$data['options']['image'] = $product_info->hinhSp;
    	Cart::add($data);
    	return Redirect::to('/show-cart');
    	
    }
    public function show_cart(){
    	$cate_product = DB::table('loai')->where('tinhTrang','0')->orderby('maLoai','desc')->get();

        $brand_product = DB::table('nha_cung_cap')->where('tinhTrang','0')->orderby('maNcc','desc')->get();
    	return view('pages.cart.show_cart')->with('category',$cate_product)->with('brand',$brand_product);
    }
    public function delete_to_cart($rowId){
        Cart::update($rowId,0);
        return Redirect::to('/show-cart');
    }
    public function update_cart_quantity(Request $request){
        $rowId = $request->rowId_cart;
        $qty = $request->cart_quantity;
        Cart::update($rowId,$qty);
        return Redirect::to('/show-cart');
    }
}
