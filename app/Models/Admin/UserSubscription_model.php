<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription_model extends Model
{
    use HasFactory;
    protected $table = 'user_subscriptions';
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
