<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customerorder_model extends Model
{
    use HasFactory;
    protected $table = 'orders';
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo(Auth_model::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(Orderitem_model::class, 'order_id');
    }
    public function address()
    {
        return $this->belongsTo(Useraddress_model::class, 'address_id');
    }
    public function subscription()
    {
        return $this->belongsTo(Usersubscription_model::class, 'user_id');
    }
    protected $fillable = [
        'status',
        'user_id',
        'address_id',
        'order_type',
        'total_amount',
        'status',
        'note',
        'order_date',
        'order_id',
        'payment_status',
        'order_status',
        'updated_at'
    ];
}
