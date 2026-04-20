<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','slug','parent_id','position','status'];

    // Relation for multi-level menus
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }
}
