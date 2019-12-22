<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'items';

    protected $fillable = ['field', 'parent_id', 'menu_id'];
    protected $hidden = ['id', 'parent_id', 'menu_id', 'created_at', 'updated_at'];

    public function parent()
    {
        return $this->belongsTo(Item::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Item::class, 'parent_id')->with('children');
    }


}
