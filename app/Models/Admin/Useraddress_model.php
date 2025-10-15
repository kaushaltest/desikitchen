<?php 
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Useraddress_model extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user_addresses';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'address_type',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'pincode',
        'is_default',
        'lat',
        'long',
        'is_active'
    ];
   
    public function address()
    {
        return $this->belongsTo(KitUser::class, 'user_id');
    }
}
