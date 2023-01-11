<?php

namespace Database\Seeders;

use App\Enums\discountServiceEnum;
use App\Enums\statusEnum;
use App\Models\Discounts\DiscountSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class discountServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addPercentOverDiscount();
        $this->addBuyCountGetFree();
        $this->addBuyCountGetDiscount();
    }

    protected function addPercentOverDiscount()
    {
        $percentOverDiscount = DiscountSetting::query()
            ->firstOrCreate(
                [
                    'discount_code' => discountServiceEnum::PERCENT_OVER_DISCOUNT->value,
                    'discount_reason' => discountServiceEnum::PERCENT_OVER_DISCOUNT->value,
                ],
                [
                    'discount_description'       => "PERCENT OVER DISCOUNT",
                    'discount_setting'     => json_encode([
                        'total_price'     => 1000,
                        'discount'     => 10,
                    ]),
                    'status'     => statusEnum::ACTIVE->value,
                ]
            );
    }

    protected function addBuyCountGetFree()
    {
        $buyCountGetFree = DiscountSetting::query()
            ->firstOrCreate(
                [
                    'discount_code' => discountServiceEnum::BUY_COUNT_GET_FREE->value,
                    'discount_reason' => discountServiceEnum::BUY_COUNT_GET_FREE->value,
                ],
                [
                    'discount_description'       => "BUY 6 GET 1 FREE ",
                    'discount_setting'     => json_encode([
                        'category'         => 2,
                        'buy_count'        => 6,
                        'get_free'         => 1
                    ]),
                    'status'     => statusEnum::ACTIVE->value,
                ]
            );
    }

    protected function addBuyCountGetDiscount()
    {
        $buyCountGetDiscount = DiscountSetting::query()
            ->firstOrCreate(
                [
                    'discount_code' => discountServiceEnum::BUY_COUNT_GET_DISCOUNT->value,
                    'discount_reason' => discountServiceEnum::BUY_COUNT_GET_DISCOUNT->value,
                ],
                [
                    'discount_description'       => "BUY 2 GET DISCOUNT ",
                    'discount_setting'     => json_encode([
                        'category'             => 1,
                        'buy_count'            => 2,
                        'get_discount'         => 20,
                        'order_by'             => 'min'
                    ]),
                    'status'     => statusEnum::ACTIVE->value,
                ]
            );
    }

}
