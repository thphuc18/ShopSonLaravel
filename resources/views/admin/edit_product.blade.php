@extends('admin_layout')
@section('admin_content')
<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Cập nhật sản phẩm
                        </header>
                        <?php
                            $message = Session::get('message');
                            if ($message){
                                echo '<span class="text-alert">' .$message.'</span>';
                                Session::put('message',null);
                            }
                        ?>
                        <div class="panel-body">
                            <div class="position-center">
                                @foreach($edit_product as $key => $pro)
                                <form role="form" action="{{URL::to('/update-product/'.$pro->maSP)}}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên sản phẩm</label>
                                    <input type="text" name="product_name" class="form-control" id="exampleInputEmail1" value="{{ $pro->tenSP }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Giá sản phẩm</label>
                                    <input type="text" name="product_price" class="form-control" id="exampleInputEmail1" value="{{$pro->giaSP}}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Hình ảnh sản phẩm</label>
                                    <input type="file" name="product_image" class="form-control" id="exampleInputEmail1"><img src="{{URL::to('public/uploads/product/'.$pro->hinhSp)}}" height="100" width="100">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Thành phần sản phẩm</label>
                                    <input type="text" name="product_thanhphan" class="form-control" id="exampleInputEmail1" value="{{ $pro->thanhPhan}}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Xuất sứ sản phẩm</label>
                                    <input type="text" name="product_xuatsu" class="form-control" id="exampleInputEmail1" value="{{ $pro->xuatSu}}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Kích thước sản phẩm</label>
                                    <input type="text" name="product_kichthuoc" class="form-control" id="exampleInputEmail1" value="{{ $pro->kichThuoc}}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Số lượng sản phẩm</label>
                                    <input type="text" name="product_soluong" class="form-control" id="exampleInputEmail1" value="{{ $pro->soLuong}}">
                                </div>
                                {{--< --div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả sản phẩm</label>
                                    <textarea style="resize: none" rows="8" class="form-control" name="product_desc" id="exampleInputPassword1" >{{ $pro->product_desc }}</textarea>
                                </div>
                                 <div class="form-group">
                                    <label for="exampleInputPassword1">Nội dung sản phẩm</label>
                                    <textarea style="resize: none" rows="8" class="form-control" name="product_content" id="exampleInputPassword1" >{{ $pro->product_content }}</textarea>
                                </div>--}}
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Danh mục sản phẩm</label>
                                     <select name="product_cate" class="form-control input-sm m-bot15" >
                                       @foreach($cate_product as $key => $cate)
                                       @if($cate->maLoai==$pro->maLoai)
                                        <option selected value="{{$cate->maLoai}}">{{$cate->tenLoai}}</option>
                                        @else
                                        <option value="{{$cate->maLoai}}">{{$cate->tenLoai}}</option>
                                        @endif
                                        @endforeach
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Thương hiệu sản phẩm</label>
                                     <select name="product_brand" class="form-control input-sm m-bot15">
                                        @foreach($brand_product as $key => $brand)
                                        @if($brand->maNcc==$pro->maNcc)
                                        <option selected value="{{$brand->maNcc}}">{{$brand->tenNcc}}</option>
                                        @else
                                        <option value="{{$brand->maNcc}}">{{$brand->tenNcc}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Hiển thị</label>
                                     <select name="product_status" class="form-control input-sm m-bot15">
                                        <option value="0">Ẩn</option>
                                        <option value="1">Hiển thị</option>
                                    </select>
                                </div>
                                
                                <button type="submit" name="add_product" class="btn btn-info">Cập nhật sản phẩm</button>
                            </form>
                            @endforeach
                            </div>

                        </div>
                    </section>

            </div>
@endsection