<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();

class HomeController extends Controller
{
    public function index(){
    	$cate_product = DB::table('loai')->where('tinhTrang','0')->orderby('maLoai','desc')->get();

        $brand_product = DB::table('nha_cung_cap')->where('tinhTrang','0')->orderby('maNcc','desc')->get();

        //$all_product = DB::table('tbl_product')
        //->join('loai','loai.maLoai','=','tbl_product.category_id')
        //->join('nha_cung_cap','nha_cung_cap.maNcc','=','tbl_product.brand_id')->orderby('tbl_product.product_id','desc')->get();
        $all_product = DB::table('san_pham')->where('tinhTrang1','0')->orderby('maSP','desc')->limit(4)->get();

    	return view('pages.home')->with('category',$cate_product)->with('brand',$brand_product)->with('all_product',$all_product);
    }
    public function search(Request $request){
    	$keywords = $request->keywords_submit;

    	$cate_product = DB::table('loai')->where('tinhTrang','0')->orderby('maLoai','desc')->get();

        $brand_product = DB::table('nha_cung_cap')->where('tinhTrang','0')->orderby('maNcc','desc')->get();

        //$all_product = DB::table('tbl_product')
        //->join('loai','loai.maLoai','=','tbl_product.category_id')
        //->join('nha_cung_cap','nha_cung_cap.maNcc','=','tbl_product.brand_id')->orderby('tbl_product.product_id','desc')->get();
        $search_product = DB::table('san_pham')->where('tenSP','like','%'.$keywords.'%')->get();

    	return view('pages.sanpham.search')->with('category',$cate_product)->with('brand',$brand_product)->with('search_product',$search_product);
    }
}
