<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

class CategoryProduct extends Controller
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
    public function add_category_product(){
        $this->AuthLogin();
    	return view('admin.add_category_product');
    }
    public function all_category_product(){
        $this->AuthLogin();
    	$all_category_product = DB::table('loai')->get();
    	$manager_category_product = view('admin.all_category_product')->with('all_category_product',$all_category_product);
    	return view('admin_layout')->with('admin.all_category_product',$manager_category_product);
    }
    public function save_category_product(Request $request){
        $this->AuthLogin();
    	$data = array();
        $id = array();
        $id = DB::table('san_pham')->value('maSP');
    	$data['tenLoai'] = $request->category_product_name;
    	$data['SAN_PHAMmaSP'] = $id;
    	$data['tinhTrang'] = $request->category_product_status;

    	DB::table('loai')->insert($data);
    	Session::put('message','Thêm danh mục son thành công!');
    	return Redirect::to('add-category-product');
    }
    public function unactive_category_product($category_product_id){
        $this->AuthLogin();
    	DB::table('loai')->where('maLoai',$category_product_id)->update(['tinhTrang'=>1]);
    	Session::put('message','Không kích hoạt danh mục son thành công!');
    	return Redirect::to('all-category-product');
    }
    public function active_category_product($category_product_id){
        $this->AuthLogin();
    	DB::table('loai')->where('maLoai',$category_product_id)->update(['tinhTrang'=>0]);
    	Session::put('message','Kích hoạt danh mục son thành công!');
    	return Redirect::to('all-category-product');
    }
    public function edit_category_product($category_product_id){
        $this->AuthLogin();
    	$edit_category_product = DB::table('loai')->where('maLoai',$category_product_id)->get();
    	$manager_category_product = view('admin.edit_category_product')->with('edit_category_product',$edit_category_product);
    	return view('admin_layout')->with('admin.edit_category_product',$manager_category_product);
    }
    public function update_category_product(Request $request,$category_product_id){
        $this->AuthLogin();
    	$data = array();
        $id = array();
        $id = DB::table('san_pham')->value('maSP');
    	$data['tenLoai'] = $request->category_product_name;
        $data['SAN_PHAMmaSP'] = $id;
    	$data['tinhTrang'] = $request->category_product_desc;
    	DB::table('loai')->where('maLoai',$category_product_id)->update($data);
    	Session::put('message','Cập nhật danh mục son thành công!');
    	return Redirect::to('all-category-product');
    }
    public function delete_category_product($category_product_id){
        $this->AuthLogin();
    	DB::table('loai')->where('maLoai',$category_product_id)->delete();
    	Session::put('message','Xóa danh mục sản phẩm thành công!');
    	return Redirect::to('all-category-product');
    }

    //End Function Admin Page
    public function show_category_home($category_id){
        $cate_product = DB::table('loai')->where('tinhTrang','0')->orderby('maLoai','desc')->get();

        $brand_product = DB::table('nha_cung_cap')->where('tinhTrang','0')->orderby('maNcc','desc')->get();
        $category_by_id = DB::table('san_pham')->where('maLoai',$category_id)->get();

        $category_name = DB::table('loai')->where('loai.maLoai',$category_id)->limit(1)->get();
        return view('pages.category.show_category')->with('category',$cate_product)->with('brand',$brand_product)->with('category_by_id',$category_by_id)->with('category_name',$category_name);
    }


}
