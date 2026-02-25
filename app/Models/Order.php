<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'status',
        'total',
        'kitchen_status',
        'waiter_id',
		'payment_method',
		'payment_status'
    ];

    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';

    // ================= RELATIONS =================

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function waiter()
    {
        return $this->belongsTo(User::class,'waiter_id');
    }

    // ================= ORDER NUMBER =================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {

            $last = self::latest('id')->first();
            $number = $last ? $last->id + 1 : 1;

            $order->order_no = 'ORD-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }
}