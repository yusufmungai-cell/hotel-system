<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['code' => '1000', 'name' => 'Cash', 'type' => 'asset'],
            ['code' => '1100', 'name' => 'Bank', 'type' => 'asset'],
            ['code' => '1200', 'name' => 'Staff Loan Receivable', 'type' => 'asset'],
            ['code' => '1300', 'name' => 'Inventory', 'type' => 'asset'],

            ['code' => '2000', 'name' => 'Salaries Payable', 'type' => 'liability'],
            ['code' => '2100', 'name' => 'Statutory Payable', 'type' => 'liability'],
            ['code' => '2200', 'name' => 'Suppliers Payable', 'type' => 'liability'],

            ['code' => '3000', 'name' => 'Owner Capital', 'type' => 'equity'],

            ['code' => '4000', 'name' => 'Room Revenue', 'type' => 'income'],
            ['code' => '4100', 'name' => 'Restaurant Revenue', 'type' => 'income'],

            ['code' => '5000', 'name' => 'Salary Expense', 'type' => 'expense'],
            ['code' => '5100', 'name' => 'Food Cost', 'type' => 'expense'],
            ['code' => '5200', 'name' => 'Utility Expense', 'type' => 'expense'],
        ];

        foreach ($accounts as $acc) {
            Account::updateOrCreate(
                ['code' => $acc['code']],
                $acc
            );
        }
    }
}