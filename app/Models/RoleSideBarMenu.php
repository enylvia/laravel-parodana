<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleSideBarMenu extends Model
{
    use HasFactory;
	protected $table = 'roles_sidebar_menu';
	protected $fillable = ['role_id','sidemenu_id','menu_access'];
	public $timestamps = false;
}
