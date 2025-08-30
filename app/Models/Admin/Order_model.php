<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_model extends Model
{
    use HasFactory;
    protected $table = 'orders';
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo(KitUser::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(Orderitem_model::class, 'order_id');
    }
    public function address()
    {
        return $this->belongsTo(Useraddress_model::class, 'address_id');
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
        'created_by',
        'payment_status'
    ];
}
