<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

class BrandProduct extends Controller
{
    public function AuthLogin(){
        $admin_id=Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }
        else
        {
            return Redirect::to('admin')->send();
        }
    }
    public function add_brand_product(){
        $this->AuthLogin();
    	return view('admin.add_brand_product');
    }
    public function all_brand_product(){
        $this->AuthLogin();
    	$all_brand_product = DB::table('nha_cung_cap')->get();
    	$manager_brand_product = view('admin.all_brand_product')->with('all_brand_product',$all_brand_product);
    	return view('admin_layout')->with('admin.all_brand_product',$manager_brand_product);
    }
    public function save_brand_product(Request $request){
        $this->AuthLogin();
    	$data = array();
    	$data['tenNcc'] = $request->brand_product_name;
    	$data['diaChi'] = $request->brand_product_address;
        $data['email'] = $request->brand_product_email;
        $data['dienThoai'] = $request->brand_product_phone;
    	$data['tinhTrang'] = $request->brand_product_status;

    	DB::table('nha_cung_cap')->insert($data);
    	Session::put('message','Thêm thương hiệu sản phẩm thành công!');
    	return Redirect::to('add-brand-product');
    }
    public function unactive_brand_product($brand_product_id){
        $this->AuthLogin();
    	DB::table('nha_cung_cap')->where('maNcc',$brand_product_id)->update(['tinhTrang'=>1]);
    	Session::put('message','Không kích hoạt thương hiệu sản phẩm thành công!');
    	return Redirect::to('all-brand-product');
    }
    public function active_brand_product($brand_product_id){
        $this->AuthLogin();
    	DB::table('nha_cung_cap')->where('maNcc',$brand_product_id)->update(['tinhTrang'=>0]);
    	Session::put('message','Kích hoạt thương hiệu sản phẩm thành công!');
    	return Redirect::to('all-brand-product');
    }
    public function edit_brand_product($brand_product_id){
        $this->AuthLogin();
    	$edit_brand_product = DB::table('nha_cung_cap')->where('maNcc',$brand_product_id)->get();
    	$manager_brand_product = view('admin.edit_brand_product')->with('edit_brand_product',$edit_brand_product);
    	return view('admin_layout')->with('admin.edit_brand_product',$manager_brand_product);
    }
    public function update_brand_product(Request $request,$brand_product_id){
        $this->AuthLogin();
    	$data = array();
    	$data['tenNcc'] = $request->brand_product_name;
    	$data['tinhTrang'] = $request->brand_product_desc;
    	DB::table('nha_cung_cap')->where('maNcc',$brand_product_id)->update($data);
    	Session::put('message','Cập nhật thương hiệu sản phẩm thành công!');
    	return Redirect::to('all-brand-product');
    }
    public function delete_brand_product($brand_product_id){
        $this->AuthLogin();
    	DB::table('nha_cung_cap')->where('maNcc',$brand_product_id)->delete();
    	Session::put('message','Xóa thương hiệu sản phẩm thành công!');
    	return Redirect::to('all-brand-product');
    }

    //End Function Admin Page
    public function show_brand_home($brand_id){
        $cate_product = DB::table('loai')->where('tinhTrang','0')->orderby('maLoai','desc')->get();

        $brand_product = DB::table('nha_cung_cap')->where('tinhTrang','0')->orderby('maNcc','desc')->get();
        $brand_by_id = DB::table('san_pham')->where('maNcc',$brand_id)->get();
        $brand_name = DB::table('nha_cung_cap')->where('nha_cung_cap.maNcc',$brand_id)->limit(1)->get();
        return view('pages.brand.show_brand')->with('category',$cate_product)->with('brand',$brand_product)->with('brand_by_id',$brand_by_id)->with('brand_name',$brand_name);
    }
}
