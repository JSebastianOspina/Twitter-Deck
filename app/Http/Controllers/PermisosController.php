<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class PermisosController extends Controller
{
    public function crear(){

        $role = Role::create(['name' => 'Fast']);
       //$user = Auth::user();
       //$user->assignRole("Owner");
       //$permissionNames = $user->hasRole('Owner');

      // return $permissionNames;


    }
}
