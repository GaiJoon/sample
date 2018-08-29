<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Auth\Access\AuthorizationException;
class UsersController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        $this->middleware('auth',[
            'except'=>['show','create','store','index']
        ]);

        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    public function store(Request $request)
    {

       $user = $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
//
        $user = User::insert([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),

        ]);
        Auth::login($user);
      session()->flash('success','欢迎,您将在这里开启一段新路程');
      return redirect()->route('users.show',[$user]);
    }

    public function edit(User $user)
    {

        try {
            $this->authorize('update', $user);
        } catch (AuthorizationException $e) {

//            session()->flash('','你无权访问!');
            return redirect()->back()->withErrors('你无权访问');
        }


        return view('users.edit',compact('user'));
    }

    public function update(User $user,Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:50',
            'password'=>'required|confirmed|min:6'
        ]);


        $this->authorize('update', $user);
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success','个人资料更新成功!');
        return redirect()->route('users.show',$user->id);

    }


    public function destroy(User $user)
    {
        try {
            $this->authorize('destroy', $user);
        } catch (AuthorizationException $e) {

//            session()->flash('','你无权访问!');
            return redirect()->back()->withErrors('你无权访问');
        }
        $user->delete();
        session()->flash('success','成功删除用户!');
        return back();
    }


}