<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daywisemenu_model extends Model
{
    use HasFactory;
    protected $table = 'monthly_menu_days';
    public $timestamps = false;
    
    protected $fillable = [
        'menu_date',
        'title',
        'items',
        'price',
        'image_path'
    ];
}
