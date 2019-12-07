<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Session;
use Cart;
use Illuminate\Support\Facades\Redirect;
session_start();

class CheckoutController extends Controller
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
    public function view_order($orderId){
        $this->AuthLogin();
        $order_by_id = DB::table('tbl_order')
        ->join('khach_hang','tbl_order.customer_id','=','khach_hang.maKH')
        ->join('tbl_shipping','tbl_order.shipping_id','=','tbl_shipping.shipping_id')
        ->join('tbl_order_details','tbl_order.order_id','=','tbl_order_details.order_id')
        ->select('tbl_order.*','khach_hang.*','tbl_shipping.*','tbl_order_details.*')
        ->first();
        $manager_order_by_id = view('admin.view_order')->with('order_by_id',$order_by_id);
        return view('admin_layout')->with('admin.view_order',$manager_order_by_id);
        
    }
    public function login_checkout(){
    	$cate_product = DB::table('loai')->where('tinhTrang','0')->orderby('maLoai','desc')->get();

        $brand_product = DB::table('nha_cung_cap')->where('tinhTrang','0')->orderby('maNcc','desc')->get();

        
    	return view('pages.checkout.login_checkout')->with('category',$cate_product)->with('brand',$brand_product);
    }
    public function add_customer(Request $request){
    	$data = array();
    	$data['tenKH'] = $request->customer_name;
    	$data['email'] = $request->customer_email;
    	$data['matKhau'] = md5($request->customer_password);
    	$data['dienThoai'] = $request->customer_phone;
    	$data['diaChi'] = $request->customer_address;
    	$data['gioiTinh'] = $request->customer_sex;
    	$data['ngaySinh'] = $request->customer_birthday;

    	$customer_id = DB::table('khach_hang')->insertGetId($data);

    	Session::put('maKH',$customer_id);
    	Session::put('tenKH',$request->customer_name);
    	return Redirect::to('/show-checkout');
    }
    public function show_checkout(){
    	$cate_product = DB::table('loai')->where('tinhTrang','0')->orderby('maLoai','desc')->get();

        $brand_product = DB::table('nha_cung_cap')->where('tinhTrang','0')->orderby('maNcc','desc')->get();

        
    	return view('pages.checkout.show_checkout')->with('category',$cate_product)->with('brand',$brand_product);
    }
    public function save_checkout_customer(Request $request){
        $data = array();
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_address'] = $request->shipping_address;
        $data['shipping_notes'] = $request->shipping_notes;
        

        $shipping_id = DB::table('tbl_shipping')->insertGetId($data);

        Session::put('shipping_id',$shipping_id);
       
        return Redirect::to('/payment');
    }
    public function payment(){
        $cate_product = DB::table('loai')->where('tinhTrang','0')->orderby('maLoai','desc')->get();

        $brand_product = DB::table('nha_cung_cap')->where('tinhTrang','0')->orderby('maNcc','desc')->get();

        return view('pages.checkout.payment')->with('category',$cate_product)->with('brand',$brand_product);
    }
    public function order_place(Request $request){
        //lấy hình thức thanh toán
        $data = array();
        $data['payment_method'] = $request->payment_option;
        $data['payment_status'] = 'Đang chờ xử lí';
        $payment_id = DB::table('tbl_payment')->insertGetId($data);

        //order
        $order_data = array();
        $order_data['KHACH_HANGmaKH'] = Session::get('maKH');
        $order_data['shipping_id'] = Session::get('shipping_id');
        $order_data['payment_id'] = $payment_id;
        $order_data['thanhTien'] = Cart::total();
        $order_data['trangThai'] = 'Đang chờ xử lí';
        $order_id = DB::table('don_dat_hang')->insertGetId($order_data);

        //order details
        $content = Cart::content();
        foreach ($content as $v_content) {
            $order_d_data = array();
            $order_d_data['DON_DAT_HANGmaDDH'] = $order_id;
            $order_d_data['SAN_PHAMmaSP'] = $v_content->id;
            $order_d_data['tenSP'] = $v_content->name;
            $order_d_data['tongTien'] = $v_content->price;
            $order_d_data['soLuong'] = $v_content->qty;
            
            DB::table('chi_tiet_don_hang')->insertGetId($order_d_data);
        }
        if($data['payment_method']==1){
            echo 'Thanh toán thẻ ATM';
        }else{
            Cart::destroy();
            $cate_product = DB::table('loai')->where('tinhTrang','0')->orderby('maLoai','desc')->get();

            $brand_product = DB::table('nha_cung_cap')->where('tinhTrang','0')->orderby('maNcc','desc')->get();

            return view('pages.checkout.handcash')->with('category',$cate_product)->with('brand',$brand_product);
        }
        
        
       
        //return Redirect::to('/payment');
    }
    public function logout_checkout(){
        Session::flush();
        return Redirect::to('/login-checkout');
    }
    public function login_customer(Request $request){
        $email = $request->email_account;
        $password = md5($request->password_account);
        $result = DB::table('khach_hang')->where('email',$email)->where('matKhau',$password)->first();
        if($result){
            Session::put('maKH',$result->maKH);
            return Redirect::to('/show-checkout');
        }
        else
        {
            return Redirect::to('/login-checkout');
        } 
    }
    public function manage_order(){

        $this->AuthLogin();
        $all_order = DB::table('tbl_order')
        ->join('khach_hang','tbl_order.customer_id','=','khach_hang.maKH')
        ->select('tbl_order.*','khach_hang.tenKH')->orderby('tbl_order.order_id','desc')->get();
        $manager_order = view('admin.manage_order')->with('all_order',$all_order);
        return view('admin_layout')->with('admin.manage_order',$manager_order);
    }
}
