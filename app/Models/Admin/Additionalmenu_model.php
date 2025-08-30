<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Additionalmenu_model extends Model
{
    use HasFactory;
    protected $table = 'monthly_menu_extras';
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'is_active',
        'image_path'
    ];
}
