<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diningmenu_model extends Model
{
    use HasFactory;
    protected $table = 'dining_menus';
    public $timestamps = false;
    
    public function category()
    {
        return $this->belongsTo(Category_model::class, 'category_id');
    }
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'is_active',
        'image_path'
    ];
}
