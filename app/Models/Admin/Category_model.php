<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category_model extends Model
{
    use HasFactory;
    protected $table = 'alacarte_category';
    public $timestamps = false;

    public function alacartemenus()
    {
        return $this->hasMany(Alacartemenu_model::class, 'category_id');
    }
    
    protected $fillable = [
        'category'
    ];
}
