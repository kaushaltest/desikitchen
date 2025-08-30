<?php 
namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Subscription_model extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'subscription_plans';
    public $timestamps = false;

    public function userSubscriptions()
    {
        return $this->hasMany(Usersubscription_model::class, 'plan_id', 'id');
    }
}
