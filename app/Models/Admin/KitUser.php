<?php

namespace App\Models\Admin;

use App\Notifications\CustomResetPassword;
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
        'is_active',
        'is_created_by_admin',
        'country_code'
    ];
    public function address()
    {
        return $this->hasMany(Useraddress_model::class, 'user_id');
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function sendPasswordResetNotification($token)
    {
        $isAdmin = request()->is('admin/*');

        $url = $isAdmin
        ? url('/admin/reset-password/' . $token . '?email=' . $this->email)
        : url('/reset-password/' . $token . '?email=' . $this->email);
        $this->notify(new CustomResetPassword($token, $url));
    }
}
