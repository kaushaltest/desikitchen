<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription_model extends Model
{
    use HasFactory;
    protected $table = 'subscription_plans';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
        'price',
        'total_meals',
        'days',
        'is_active'
    ];
}
