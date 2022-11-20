<?php

namespace Tocaan\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MenuItems extends Model
{
    use HasTranslations;

    protected $table = null;

    protected $fillable = ['label', 'link', 'parent', 'sort', 'class', 'menu', 'depth', 'role_id','type','json_data'];
    public $translatable 	= ['label','link'];

    protected $casts = [
        'json_data' => 'array',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


    public function __construct(array $attributes = [])
    {
        $this->table = config('menu.table_prefix') . config('menu.table_name_items');
    }

    public function itemable()
    {
        return $this->morphTo();
    }

    public function getsons($id)
    {
        return $this->where("parent", $id)->get();
    }
    public function getall($id)
    {
        return $this->where("menu", $id)->orderBy("sort", "asc")->get();
    }

    public static function getNextSortRoot($menu)
    {
        return self::where('menu', $menu)->max('sort') + 1;
    }

    public function parent_menu()
    {
        return $this->belongsTo('Tocaan\Menu\Models\Menus', 'menu');
    }

    public function child()
    {
        return $this->hasMany('Tocaan\Menu\Models\MenuItems', 'parent')->orderBy('sort', 'ASC');
    }
}
