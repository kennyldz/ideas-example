<?php

namespace App\Models\Discounts;

use App\Models\Customers\Customer;
use App\Models\Orders\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountSetting extends Model
{
    use HasFactory;

    protected $fillable=[
        "id",
        "discount_code",
        "discount_reason",
        "discount_description",
        "discount_setting",
        "status",
    ];

}
