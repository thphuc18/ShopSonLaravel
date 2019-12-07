<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

class ProductController extends Controller
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
    public function add_product(){
        $this->AuthLogin();
        $cate_product = DB::table('loai')->orderby('maLoai','desc')->get();
        $brand_product = DB::table('nha_cung_cap')->orderby('maNcc','desc')->get();
        return view('admin.add_product')->with('cate_product',$cate_product)->with('brand_product',$brand_product);
    }
    public function all_product(){
        $this->AuthLogin();
    	$all_product = DB::table('san_pham')
        ->join('loai','loai.maLoai','=','san_pham.maLoai')
        ->join('nha_cung_cap','nha_cung_cap.maNcc','=','san_pham.maNcc')->orderby('san_pham.maSP','desc')->get();
    	$manager_product = view('admin.all_product')->with('all_product',$all_product);
    	return view('admin_layout')->with('admin.all_product',$manager_product);
    }
    public function save_product(Request $request){
        $this->AuthLogin();
    	$data = array();
    	$data['tenSP'] = $request->product_name;
        $data['giaSP'] = $request->product_price;
        $data['thanhPhan'] = $request->product_thanhphan;
    	$data['xuatSu'] = $request->product_xuatsu;
        $data['kichThuoc'] = $request->product_kichthuoc;
        $data['maLoai'] = $request->product_cate;
        $data['maNcc'] = $request->product_brand;
    	$data['tinhTrang1'] = $request->product_status;
        $data['soLuong'] = $request->product_soluong;
        
        $get_image = $request->file('product_image');
        if($get_image){
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image.rand(0,99).'-'.$get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/product',$new_image);
            $data['hinhSP']=$new_image;
            DB::table('san_pham')->insert($data);
            Session::put('message','Thêm sản phẩm thành công!');
            return Redirect::to('all-product');
        }
        $data['hinhSP']='';
    	DB::table('san_pham')->insert($data);
    	Session::put('message','Thêm sản phẩm thành công!');
    	return Redirect::to('all-product');
    }
    public function unactive_product($product_id){
        $this->AuthLogin();
    	DB::table('san_pham')->where('maSP',$product_id)->update(['tinhTrang1'=>1]);
    	Session::put('message','Không kích hoạt sản phẩm thành công!');
    	return Redirect::to('all-product');
    }
    public function active_product($product_id){
        $this->AuthLogin();
    	DB::table('san_pham')->where('maSP',$product_id)->update(['tinhTrang1'=>0]);
    	Session::put('message','Kích hoạt sản phẩm thành công!');
    	return Redirect::to('all-product');
    }
    public function edit_product($product_id){
        $this->AuthLogin();
        $cate_product = DB::table('loai')->orderby('maLoai','desc')->get();
        $brand_product = DB::table('nha_cung_cap')->orderby('maNcc','desc')->get();
        
    	$edit_product = DB::table('san_pham')->where('maSP',$product_id)->get();
    	$manager_product = view('admin.edit_product')->with('edit_product',$edit_product)->with('cate_product',$cate_product)->with('brand_product',$brand_product);
    	return view('admin_layout')->with('admin.edit_product',$manager_product);
    }
    public function update_product(Request $request,$product_id){
        $this->AuthLogin();
    	$data = array();
    	$data['tenSP'] = $request->product_name;
        $data['giaSP'] = $request->product_price;
        $data['thanhPhan'] = $request->product_thanhphan;
        $data['xuatSu'] = $request->product_xuatsu;
        $data['kichThuoc'] = $request->product_kichthuoc;
        $data['maLoai'] = $request->product_cate;
        $data['maNcc'] = $request->product_brand;
        $data['tinhTrang1'] = $request->product_status;
        $data['soLuong'] = $request->product_soluong;
        $get_image = $request->file('product_image');
        if($get_image){
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image.rand(0,99).'-'.$get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/product',$new_image);
            $data['hinhSP']=$new_image;
            DB::table('san_pham')->where('maSP',$product_id)->update($data);
            Session::put('message','Cập nhật sản phẩm thành công!');
            return Redirect::to('all-product');
        }
    	DB::table('san_pham')->where('maSP',$product_id)->update($data);
    	Session::put('message','Cập nhật sản phẩm không thành công!');
    	return Redirect::to('all-product');
    }
    public function delete_product($product_id){
    	DB::table('san_pham')->where('maSP',$product_id)->delete();
    	Session::put('message','Xóa thương hiệu sản phẩm thành công!');
    	return Redirect::to('all-product');
    }

    //End admin page
    public function details_product($product_id){
        $cate_product = DB::table('loai')->where('tinhTrang','0')->orderby('maLoai','desc')->get();

        $brand_product = DB::table('nha_cung_cap')->where('tinhTrang','0')->orderby('maNcc','desc')->get();

        $details_product = DB::table('san_pham')
        ->join('loai','loai.maLoai','=','san_pham.maLoai')
        ->join('nha_cung_cap','nha_cung_cap.maNcc','=','san_pham.maNcc')->where('san_pham.maSP',$product_id)->get();
        foreach($details_product as $key => $value){
            $category_id = $value->maLoai;
        }
        $related_product = DB::table('san_pham')
        ->join('loai','loai.maLoai','=','san_pham.maLoai')
        ->join('nha_cung_cap','nha_cung_cap.maNcc','=','san_pham.maNcc')->where('loai.maLoai',$category_id)->whereNotIn('san_pham.maSP',[$product_id])->get();
        return view('pages.sanpham.show_details')->with('category',$cate_product)->with('brand',$brand_product)->with('product_details',$details_product)->with('related',$related_product);
    }
}
