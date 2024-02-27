<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SideBarMenu extends Model
{
    use HasFactory;
	protected $table = 'sidebar_menu';
    protected $fillable = ['title','display','icon','url'];
	
	//public function roles()
    //{
        //return $this->belongsToMany(Role::class)->withPivot('role_id','sidemenu_id','menu_access');
		//return $this->belongsToMany(Role::class);
	//	return $this->belongsToMany('App\Models\SideBarMenu')->withPivot('sidemenu_id', 'menu_access');
    //}
	
	public function setUrlAttribute($url)
    {
        $this->attributes['url'] = trim($url) !== '' ? $url : null;
    }

    public function getMaxOrder()
    {
        $menu = $this->orderBy('orders', 'desc')->first();
        if (isset($menu)) {
            return $menu->order;
        }

        return 0;
    }

    public function generateMenu($menu, $parentID = 0)
    {
        $result = null;

        foreach ($menu as $item) {
            if ($item->id_parent == $parentID) {
                $imageName = ($item->menu_access) ? 'publish_16x16.png' : 'not_publish_16x16.png';

                $result .= "<li class='dd-item' data-order='{$item->orders}' data-id='{$item->id}'>
                <button type='button' data-action='collapse'>Collapse</button>
                <button type='button' data-action='expand' style='display: none;'>Expand</button>
                <div class='dd-handle'></div>
                    <div class='dd-content'><span>{$item->display}</span>
                    <div class='ns-actions'>                    
                        <a title='Publish Menu' id='{$item->id}' class='publish' href='#'><img id='publish-image-".$item->id."' alt='Publish' src='".url('/').'/images/'.$imageName."'></a>
                        <a id='Edit' data-target='#Edit-{$item->id}' data-toggle='modal' title='Edit Menu' class='edit-menu'><img alt='Edit' src='".url('/').'/images/edit.png'."'></a>
                        <a id='Hapus' class='delete-menu' data-target='#Hapus-{$item->id}' data-toggle='modal'><img alt='Delete' src='".url('/').'/images/cross.png'."'></a>         
                        <input type='hidden' value='1' name='menu_id'>
                    </div>
                </div>".$this->generateMenu($menu, $item->id).'
            </li>';
            }
        }

        return $result ? "\n<ol class=\"dd-list\">\n$result</ol>\n" : null;
    }

    public function getMenuHTML($items)
    {
        return $this->generateMenu($items);
    }
    
   public function parseJsonArray($jsonArray, $parentID = 0)
    {
        $return = array();

        foreach ($jsonArray as $subArray) {
            $returnSubArray = array();

            if (isset($subArray['children'])) {
                $returnSubArray = $this->parseJsonArray($subArray['children'], $subArray['id']);
            }

            $return[] = array('id' => $subArray['id'], 'parentID' => $parentID);
            $return = array_merge($return, $returnSubArray);
        }

        return $return;
        //echo "<pre>";
        //print_r($return);
        //echo "</pre>";
        //exit();   
    }
    
    public function changeParentById($data)
    {
        foreach ($data as $k => $v) {
            $item = $this->find($v['id']);
            $item->id_parent = $v['parentID'];
            $item->orders = $k + 1;
            $item->save();
        }
    }
	
	public function roles() {
		return $this->belongsToMany(Role::class, 'roles_sidebar_menu');

	}
}
