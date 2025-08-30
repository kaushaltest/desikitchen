<?php 
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class KitUser extends Authenticatable
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
    public function address()
    {
        return $this->hasMany(Useraddress_model::class, 'user_id');
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
