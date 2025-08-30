<?php

namespace App\Models\Customer;

use App\Models\Admin\UserSubscription_model;
use App\Models\Customer\Usersubscription_model as CustomerUsersubscription_model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Table_model extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tables';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'capicity',
        'is_active',
        'user_id'
    ];
}
