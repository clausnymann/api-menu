<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';

    protected $fillable = ['field', 'max_depth', 'max_children'];
    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function rootItems()
    {
        return $this->hasMany(Item::class, 'menu_id');
    }

}
