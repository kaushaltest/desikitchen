<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usersubscription_model extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user_subscriptions';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'plan_id',
        'meals_remaining',
        'start_date',
        'end_date',
        'status',
        'created_at'
    ];
    public function plan()
    {
        return $this->belongsTo(Subscription_model::class, 'plan_id', 'id');
    }
}
