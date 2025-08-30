<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partyemenu_model extends Model
{
    use HasFactory;
    protected $table = 'party_menus';
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'description',
        'price_per_kg',
        'price_per_qty',
        'is_active',
        'image_path'
    ];
}
