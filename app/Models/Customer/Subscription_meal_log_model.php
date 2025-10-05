<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Subscription_meal_log_model extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'subscription_meal_logs';
    public $timestamps = false;
    protected $fillable = [
        'user_subscription_id',
        'order_id',
        'meal_used'
    ];
    public function plan()
    {
        return $this->belongsTo(Subscription_model::class, 'plan_id', 'id');
    }
}
