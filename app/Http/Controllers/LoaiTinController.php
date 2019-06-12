<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TheLoai;
use App\LoaiTin;

class LoaiTinController extends Controller
{
    public function getDanhSach()
    {
        $loaitin = LoaiTin::all();
        return view('admin.loaitin.danhsach', ['loaitin'=>$loaitin]);
    }

    public function getSua($id)
    {
        $loaitin = LoaiTin::find($id);
        $theloai = TheLoai::all();
        return view('admin.loaitin.sua', ['loaitin'=>$loaitin, 'theloai'=>$theloai]);
    }

    public function postSua(Request $request, $id)
    {
        $loaitin = LoaiTin::find($id);
        $this->validate($request,
        [
            'Ten'=>'required|unique:LoaiTin,Ten|min:3|max:100'
        ], 
        [
            'Ten.required'=>"Bạn chưa nhập tên loại tin",
            'Ten.unique'=>"Tên thể loại đã tồn tại",
            'Ten.min'=>'Độ dài tên phải ít nhất 3 kí tự',
            'Ten.max'=>'Độ dài tên không quá 100 kí tự'
        ]);

        $loaitin->Ten = $request->Ten;
        $loaitin->TenKhongDau = changeTitle($request->Ten); 
        $loaitin->idTheLoai = $request->TheLoai;
        $loaitin->save();
            
        return redirect('admin/loaitin/sua/'.$id)->with('thongbao', 'Sửa thành công');
    }

    public function getThem()
    {
        $theloai = TheLoai::all();
        return view('admin.loaitin.them', ['theloai'=>$theloai]);
    }

    public function postThem(Request $request)
    {
        // echo $request->Ten;
        $this->validate($request,
        [
            'Ten' => 'required|min:3|max:100|unique:TheLoai,Ten',
            'TheLoai' => 'required'
        ],
        [
            'Ten.required'=>"Bạn chưa nhập tên thể loại",
            'Ten.unique'=>"Đã tồn tại",
            'Ten.min'=>'Độ dài tên phải ít nhất 3 kí tự',
            'Ten.max'=>'Độ dài tên không quá 100 kí tự',
            'TheLoai.required'=> 'Bạn chưa chọn thể loại'
        ]);
        $loaitin = new LoaiTin;
        $loaitin->Ten = $request->Ten;
        $loaitin->TenKhongDau = changeTitle($request->Ten);
        $loaitin->idTheLoai = $request->TheLoai;

        // echo changeTitle($request->Ten);

        $loaitin->save();

        return redirect('admin/loaitin/them')->with('thongbao','Thêm thành công');
    }

    public function getXoa($id)
    {
        $loaitin = LoaiTin::find($id);
        $loaitin->delete();

        return redirect('admin/loaitin/danhsach')->with('thongbao', 'Bạn đã xóa thành công');
    }
}
