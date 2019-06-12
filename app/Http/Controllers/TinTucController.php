<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TheLoai;
use App\LoaiTin;
use App\TinTuc;
use App\Comment;

class TinTucController extends Controller
{
    public function getDanhSach()
    {
        $tintuc = TinTuc::orderby('id', 'DESC')->get();
        return view('admin.tintuc.danhsach', ['tintuc'=>$tintuc]);
    }

    public function getSua($id)
    {
        $tintuc = TinTuc::find($id);
        $theloai = TheLoai::all();
        $loaitin = LoaiTin::all();
        return view('admin.tintuc.sua', ['loaitin'=>$loaitin, 'theloai'=>$theloai, 'tintuc'=>$tintuc]);
    }

    public function postSua(Request $request, $id)
    {
        $tintuc = TinTuc::find($id);
        $this->validate($request,
        [
            'TieuDe' => 'required|min:3|unique:TinTuc,TieuDe',
            'LoaiTin' => 'required',
            'TomTat'  => 'required',
            'NoiDung' => 'required'
        ],
        [
            'LoaiTin.required'=>"Bạn chưa chọn loại tin",
            'TieuDe.required'=>"Bạn chưa nhập tiêu đề",
            'TieuDe.unique'=>"Đã tồn tại",
            'TieuDe.min'=>'Độ dài tiêu đề phải ít nhất 3 kí tự',
            'TomTat.required'=>'Bạn chưa nhập tóm tắt',
            'NoiDung.required'=>'Bạn chưa nhập nội dung',
        ]);

        $tintuc->TieuDe = $request->TieuDe;
        $tintuc->TieuDeKhongDau = changeTitle($request->TieuDe);
        $tintuc->idLoaiTin = $request->LoaiTin;
        $tintuc->TomTat = $request->TomTat;
        $tintuc->NoiDung = $request->NoiDung;
        $tintuc->NoiBat = $request->NoiBat;
        $tintuc->SoLuotXem = 0;
       

        if($request->hasFile('Hinh'))
        {
            $file = $request->file('Hinh');
            $lastName = $file->getClientOriginalExtension();
            if($lastName!='jpg' && $lastName!='png' && $lastName!='jpeg')
            {
                return redirect('admin/tintuc/them')->with('loi','Bạn nhập sai file ảnh');
            }
            $name = $file->getClientOriginalName();
            $Hinh = str_random(4) . "_" . $name;
            while(file_exists("upload/tintuc/".$Hinh))
            {
                $Hinh = str_random(4) . "_" . $name;
            }
            $file->move("upload/tintuc", $Hinh);
            if($tintuc->Hinh)
            {
                unlink("upload/tintuc/".$tintuc->Hinh);
            }
            $tintuc->Hinh = $Hinh;
        }
        $tintuc->save();
            
        return redirect('admin/tintuc/sua/'.$id)->with('thongbao', 'Sửa thành công');
    }

    public function getThem()
    {
        $theloai = TheLoai::all();
        $loaitin = LoaiTin::all();

        return view('admin.tintuc.them', ['theloai'=>$theloai, 'loaitin'=>$loaitin]);
    }

    public function postThem(Request $request)
    {
        // echo $request->Ten;
        $this->validate($request,
        [
            'TieuDe' => 'required|min:3|unique:TinTuc,TieuDe',
            'LoaiTin' => 'required',
            'TomTat'  => 'required',
            'NoiDung' => 'required'
        ],
        [
            'LoaiTin.required'=>"Bạn chưa chọn loại tin",
            'TieuDe.required'=>"Bạn chưa nhập tiêu đề",
            'TieuDe.unique'=>"Đã tồn tại",
            'TieuDe.min'=>'Độ dài tiêu đề phải ít nhất 3 kí tự',
            'TomTat.required'=>'Bạn chưa nhập tóm tắt',
            'NoiDung.required'=>'Bạn chưa nhập nội dung',
        ]);

        $loaitin = new LoaiTin;
        $tintuc = new TinTuc;
        $tintuc->TieuDe = $request->TieuDe;
        $tintuc->TieuDeKhongDau = changeTitle($request->TieuDe);
        $tintuc->idLoaiTin = $request->LoaiTin;
        $tintuc->TomTat = $request->TomTat;
        $tintuc->NoiDung = $request->NoiDung;
        $tintuc->NoiBat = $request->NoiBat;
        $tintuc->SoLuotXem = 0;
       

        if($request->hasFile('Hinh'))
        {
            $file = $request->file('Hinh');
            $lastName = $file->getClientOriginalExtension();
            if($lastName!='jpg' && $lastName!='png' && $lastName!='jpeg')
            {
                return redirect('admin/tintuc/them')->with('loi','Bạn nhập sai file ảnh');
            }
            $name = $file->getClientOriginalName();
            $Hinh = str_random(4) . "_" . $name;
            while(file_exists("upload/tintuc/".$Hinh))
            {
                $Hinh = str_random(4) . "_" . $name;
            }
            $file->move("upload/tintuc", $Hinh);
            $tintuc->Hinh = $Hinh;
        }
        else
        {
            $tintuc->Hinh = "";
        }
        $tintuc->save();
        return redirect('admin/tintuc/them')->with('thongbao','Thêm tin tức thành công');
    }

    public function getXoa($id)
    {
        $tintuc = TinTuc::find($id);
        $tintuc->delete();

        return redirect('admin/tintuc/danhsach')->with('thongbao', 'Bạn đã xóa thành công');
    }
}

