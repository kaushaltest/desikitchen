<?php 
namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Auth_model extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'kit_user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active'
    ];
    public function subscription()
    {
        return $this->hasMany(Usersubscription_model::class, 'user_id');
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
