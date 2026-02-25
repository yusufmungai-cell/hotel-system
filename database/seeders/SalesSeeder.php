<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;

class SalesSeeder extends Seeder
{
    public function run(): void
    {
        $menuItems = MenuItem::pluck('id')->toArray();

        if (empty($menuItems)) {
            $this->command->info('⚠ No menu items found. Please seed menu_items first.');
            return;
        }

        // Get highest existing order number safely
        $maxNumber = Order::selectRaw("MAX(CAST(SUBSTRING(order_no, 5) AS UNSIGNED)) as max_no")
            ->value('max_no');

        $nextNumber = $maxNumber ? $maxNumber + 1 : 1;

        for ($i = 1; $i <= 50; $i++) {

            $createdDate = now()->subDays(rand(0, 60));

            $orderNo = 'ORD-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            $nextNumber++;

            $order = Order::create([
                'order_no' => $orderNo,
                'status' => 'closed',
                'total' => 0,
                'payment_method' => 'cash',
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
            ]);

            $orderTotal = 0;

            $itemsCount = rand(1, 3);

            for ($j = 1; $j <= $itemsCount; $j++) {

                $menuItemId = $menuItems[array_rand($menuItems)];
                $qty = rand(1, 5);
                $price = rand(200, 1500);
                $itemTotal = $qty * $price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItemId,
                    'qty' => $qty,
                    'price' => $price,
                    'total' => $itemTotal,
                    'created_at' => $createdDate,
                    'updated_at' => $createdDate,
                ]);

                $orderTotal += $itemTotal;
            }

            $order->update([
                'total' => $orderTotal
            ]);
        }

        $this->command->info('✅ SalesSeeder completed successfully.');
    }
}
