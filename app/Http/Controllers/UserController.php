<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TheLoai;
use App\LoaiTin;
use App\User;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getDanhSach()
    {
        $user = User::all();
        return view('admin.user.danhsach', ['user'=>$user]);
    }

    public function getSua($id)
    {
        $user = User::find($id);
        return view('admin.user.sua', ['user'=>$user]);
    }

    public function postSua(Request $request, $id)
    {
        $user = User::find($id);

        $this->validate($request,
        [
            'Ten'=>'required|min:3',
            'Email'=>'required|email|unique:users,email',
            'Password'=>'required|min:3|max:32',
            'PasswordAgain'=>'required|same:Password'
        ],
        [
            'Ten.required'=>'Bạn chưa nhập tên',
            'Ten.min'=>'Tên ít nhất 3 kí tự',
            'Email.required'=>"Bạn chưa nhập email",
            'Email.email'=>"Bạn nhập sai email",
            'Email.unique'=>"Đã tồn tại email này",
            'Password.required'=>"Bạn chưa nhập mật khẩu",
            'Password.min'=>"Mật khẩu cần ít nhất 3 kí tự",
            'Password.max'=>"Mật khẩu không quá 32 kí tự",
            'PasswordAgain.same'=>"Mật khẩu nhập lại chưa khớp"
        ]);

        $user->name = $request->Ten;
        $user->email = $request->Email;
        $user->password = bcrypt($request->Password);
        $user->quyen = $request->quyen;

        $user->save();
        return redirect('admin/user/sua/'.$id)->with('thongbao', 'Sửa thành công');
    }

    public function getThem()
    {
        
        return view('admin.user.them');
    }

    public function postThem(Request $request)
    {
        $this->validate($request,
        [
            'Ten'=>'required|min:3',
            'Email'=>'required|email|unique:users,email',
            'Password'=>'required|min:3|max:32',
            'PasswordAgain'=>'required|same:Password'
        ],
        [
            'Ten.required'=>'Bạn chưa nhập tên',
            'Ten.min'=>'Tên ít nhất 3 kí tự',
            'Email.required'=>"Bạn chưa nhập email",
            'Email.email'=>"Bạn nhập sai email",
            'Email.unique'=>"Đã tồn tại email này",
            'Password.required'=>"Bạn chưa nhập mật khẩu",
            'Password.min'=>"Mật khẩu cần ít nhất 3 kí tự",
            'Password.max'=>"Mật khẩu không quá 32 kí tự",
            'PasswordAgain.same'=>"Mật khẩu nhập lại chưa khớp"
        ]);

        $user = new User;
        $user->name = $request->Ten;
        $user->email = $request->Email;
        $user->password = bcrypt($request->Password);
        $user->quyen = $request->quyen;

        $user->save();

        return redirect('admin/user/them')->with('thongbao','Thêm thành công');
    }

    public function getXoa($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect('admin/user/danhsach')->with('thongbao', 'Bạn đã xóa thành công');
    }

    public function getDangnhapAd()
    {
        return view('admin.login');
    }

    public function postDangnhapAd(Request $request)
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
            return redirect('admin/theloai/danhsach');
        }
        else
        {
            return redirect('admin/dangnhap')->with('thongbao','Đăng nhập không thành công');
        }

    }
}
