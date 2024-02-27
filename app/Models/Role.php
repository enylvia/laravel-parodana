<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
	public function permissions() {

	   return $this->belongsToMany(Permission::class,'roles_permissions');
		   
	}

	public function users() {

	   return $this->belongsToMany(User::class,'users_roles');
		   
	}
	
	public function sidebarmenu() {

		return $this->belongsToMany(SideBarMenu::class, 'roles_sidebar_menu');
	   //return $this->belongsToMany(SideBarMenu::class)->withPivot(['menu_access']);
		//return $this->belongsToMany(SideBarMenu::class, 'roles_sidebar_menu');
		//return $this->belongsToMany('App\Models\Role')->withPivot('role_id', 'sidemenu_id', 'menu_access');
	}
	
}
