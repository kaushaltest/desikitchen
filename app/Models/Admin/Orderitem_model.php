<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderitem_model extends Model
{
    use HasFactory;
    protected $table = 'order_items';
    public $timestamps = false;
    public function order()
    {
        return $this->belongsTo(Order_model::class, 'order_id');
    }
    protected $fillable = [
        'order_id',
        'item_type',
        'order_type',
        'item_id',
        'quantity',
        'unit_price',
        'total_price'
    ];
}
