<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index(){
        return view('admin.roles.index',['roles'=>Role::all()]);
    }

    public function store(){

        request()->validate([
            'name'=>['required']
        ]);

        Role::create(['name'=>Str::ucfirst(request('name')),'slug'=>Str::of(Str::lower(request('name')))->slug('-')]);
        return back();
    }

    public function destroy(Role $role){
        $role->delete();
        session()->flash('delete','Role deleted - '.$role->name);
        return back();
    }

    public function edit(Role $role){
        return view('admin.roles.edit',['role'=>$role,'permissions'=>Permission::all()]);
    }

    public function update(Role $role){

        $role->name = Str::ucfirst(request('name'));
        $role->slug = Str::of(request('name'))->slug('-');

        if (!$role->isClean('name')){
            $role->save();
            session()->flash('update','Role Updated - '.$role->name);
            return view('admin.roles.index',['roles'=>Role::all()]);
        }else{
            session()->flash('update','Role Not Updated');
            return view('admin.roles.index',['roles'=>Role::all()]);
        }
    }
}
