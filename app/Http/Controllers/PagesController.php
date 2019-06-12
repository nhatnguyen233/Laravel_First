<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\TheLoai;
use App\Slide;
use App\LoaiTin;
use App\TinTuc;
class PagesController extends Controller
{
    function __construct()
    {
        $theloai = TheLoai::all();
        $slide = Slide::all();
        view()->share('theloai',$theloai);
        view()->share('slide',$slide);

        if(Auth::check())
        {
            view()->share('nguoidung', Auth::user());
        }
    }
    function trangchu()
    {
        
        return view('pages.trangchu');
    }

    function lienhe()
    {
        
        return view('pages.lienhe');
    }

    function loaitin($id)
    {
        $loaitin = LoaiTin::find($id);
        $tintuc = TinTuc::where('idLoaiTin', $id)->paginate(5);
        return view('pages.loaitin',['loaitin'=>$loaitin, 'tintuc'=>$tintuc]);
    }

    function tintuc($id)
    {
        $tintuc = TinTuc::find($id);
        $tinNoiBat = TinTuc::where('NoiBat',1)->take(4)->get();
        $tinLienQuan = TinTuc::where('idLoaiTin',$tintuc->idLoaiTin)->take(4)->get();
        return view('pages.tintuc', ['tintuc'=>$tintuc,'tinNoiBat'=>$tinNoiBat, 'tinLienQuan'=>$tinLienQuan]);
    }

    function getDangnhap()
    {
        return view('pages.dangnhap');
    }

    function postDangnhap(Request $request)
    {
        $this->validate($request,
        [
            'email'=>'required',
            'password'=>'required|min:3|max:32'
        ],
        [
            'email.require'=>"Bạn chưa nhập email",
            'password.required'=>"Bạn chưa nhập password",
            'password.min'=>"Mật khẩu cần ít nhất 3 kí tự",
            'password.max'=>"Mật khẩu tối đa 32 kí tự"
        ]);

        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password]))
        {
            return redirect('trangchu');
        }
        else
        {
            return redirect('dangnhap')->with('thongbao','Đăng nhập không thành công');
        }
    }

    function getDangxuat()
    {
        Auth::logout();
        return redirect('trangchu');
    }
}
