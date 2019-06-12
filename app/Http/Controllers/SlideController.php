<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\LoaiTin;
use App\Comment;
use App\Slide;

class SlideController extends Controller
{
    public function getDanhSach()
    {
        $slide = Slide::all();
        return view('admin.slide.danhsach', ['slide'=>$slide]);
    }

    public function getSua($id)
    {
       $slide = Slide::find($id);
       return view('admin.slide.sua', ['slide'=>$slide]);
    }

    public function postSua(Request $request, $id)
    {
        $slide = Slide::find($id);
        $this->validate($request,
        [
            'Ten'=>'required',
            'NoiDung'=>'required'
        ],
        [
            'Ten.required'=>'Bạn chưa nhập tên',
            'NoiDung.required'=>'Bạn chưa nhập nội dung'
        ]);
        
        $slide->Ten = $request->Ten;
        $slide->NoiDung = $request->NoiDung;
        $slide->link = $request->Link; 
        if($request->hasFile('Hinh'))
        {
            $file = $request->file('Hinh');
            $lastName = $file->getClientOriginalExtension();
            if($lastName!='jpg' && $lastName!='png' && $lastName!='jpeg')
            {
                return redirect('admin/slide/them')->with('loi','Bạn nhập sai file ảnh');
            }
            $name = $file->getClientOriginalName();
            $Hinh = str_random(4) . "_" . $name;
            while(file_exists("upload/slide/".$Hinh))
            {
                $Hinh = str_random(4) . "_" . $name;
            }
            $file->move("upload/slide", $Hinh);
            $slide->Hinh = $Hinh;
        }
       
        $slide->save();

        return redirect('admin/slide/sua/'.$id)->with('thongbao', 'Sửa thành công');
    }

    public function getThem()
    {
        // $slide = Slide::all();
        return view('admin.slide.them');
    }

    public function postThem(Request $request)
    {
        $this->validate($request,
        [
            'Ten'=>'required',
            'NoiDung'=>'required'
        ],
        [
            'Ten.required'=>'Bạn chưa nhập tên',
            'NoiDung.required'=>'Bạn chưa nhập nội dung'
        ]);
        $slide = new Slide;
        $slide->Ten = $request->Ten;
        $slide->NoiDung = $request->NoiDung;
        $slide->link = $request->Link; 
        if($request->hasFile('Hinh'))
        {
            $file = $request->file('Hinh');
            $lastName = $file->getClientOriginalExtension();
            if($lastName!='jpg' && $lastName!='png' && $lastName!='jpeg')
            {
                return redirect('admin/slide/them')->with('loi','Bạn nhập sai file ảnh');
            }
            $name = $file->getClientOriginalName();
            $Hinh = str_random(4) . "_" . $name;
            while(file_exists("upload/slide/".$Hinh))
            {
                $Hinh = str_random(4) . "_" . $name;
            }
            $file->move("upload/slide", $Hinh);
            $slide->Hinh = $Hinh;
        }
        else
        {
            $slide->Hinh = "";
        }
        $slide->save();
        return redirect('admin/slide/them')->with('thongbao','Thêm slide thành công');
    }

    public function getXoa($id)
    {
        $slide = Slide::find($id);
        $slide->delete();

        return redirect('admin/slide/danhsach')->with('thongbao', 'Bạn đã xóa thành công');
    }
}

