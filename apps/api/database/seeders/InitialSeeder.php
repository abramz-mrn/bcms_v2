<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InitialSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $companyId = DB::table('companies')->insertGetId([
            'name' => 'PT. Trira Inti Utama',
            'alias' => 'PT TIU',
            'address' => 'Ruko Kemanggisan Blok O4 No. 6 Metland Cibitung',
            'city' => 'Kab. Bekasi',
            'state' => 'Jawa Barat',
            'pos' => '17530',
            'phone' => '021000000',
            'email' => 'info@trira.co.id',
            'logo' => null,
            'bank_account' => json_encode([
                'bank_name' => 'Mandiri',
                'account_number' => '156-00-2388849-0',
                'account_name' => 'PT. Trira Inti Utama',
            ]),
            'npwp' => '50.520.877.7-413.000',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $brandId = DB::table('brands')->insertGetId([
            'companies_id' => $companyId,
            'name' => 'Maroon-NET',
            'description' => 'Brand utama ISP',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $groups = [
            'Administrator' => ['*'],
            'Supervisor' => ['products', 'routers', 'brands', 'reports:view'],
            'Finance/Kasir' => ['billing', 'payments', 'invoices'],
            'Support' => ['tickets'],
            'NOC/Technician' => ['provisioning', 'router:tools'],
        ];

        $groupIds = [];
        foreach ($groups as $name => $permissions) {
            $groupIds[$name] = DB::table('users_groups')->insertGetId([
                'name' => $name,
                'permissions' => json_encode($permissions),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $users = [
            ['name' => 'Abramz', 'email' => 'abramz@example.com', 'phone' => '628111111111', 'group' => 'Administrator'],
            ['name' => 'Fandi', 'email' => 'fandi@example.com', 'phone' => '628122222222', 'group' => 'Supervisor'],
            ['name' => 'Meci', 'email' => 'meci@example.com', 'phone' => '628133333333', 'group' => 'Finance/Kasir'],
            ['name' => 'Yogi', 'email' => 'yogi@example.com', 'phone' => '628144444444', 'group' => 'Support'],
        ];

        foreach ($users as $u) {
            DB::table('users')->insert([
                'users_groups_id' => $groupIds[$u['group']],
                'companies_id' => $companyId,
                'name' => $u['name'],
                'password' => Hash::make('password'),
                'nik' => Str::random(10),
                'photo' => null,
                'phone' => $u['phone'],
                'email' => $u['email'],
                'locked' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $products = [
            ['code' => 'PKT-BASIC', 'name' => 'Paket Basic', 'price' => 200000],
            ['code' => 'PKT-MED', 'name' => 'Paket Medium', 'price' => 300000],
            ['code' => 'PKT-PREM', 'name' => 'Paket Premium', 'price' => 500000],
            ['code' => 'SOHO-20', 'name' => 'SOHO-20', 'price' => 400000],
            ['code' => 'SOHO-50', 'name' => 'SOHO-50', 'price' => 600000],
            ['code' => 'SOHO-100', 'name' => 'SOHO-100', 'price' => 900000],
        ];

        $productIds = [];
        foreach ($products as $p) {
            $productIds[$p['code']] = DB::table('products')->insertGetId([
                'code' => $p['code'],
                'name' => $p['name'],
                'type' => 'Internet Services',
                'description' => null,
                'market_segment' => 'Residensial',
                'billing_cycle' => 'Monthly',
                'price' => $p['price'],
                'tax_rate' => 10,
                'tax_included' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $routerId = DB::table('routers')->insertGetId([
            'name' => 'RTR-01',
            'location' => 'POP Dummy',
            'description' => 'Router dummy (offline)',
            'ip_address' => '10.0.0.1',
            'api_port' => 8729,
            'ssh_port' => 22,
            'api_username' => 'admin',
            'api_password' => 'admin',
            'tls_enabled' => false,
            'ssh_enabled' => false,
            'status' => 'offline',
            'sync_interval' => 300,
            'config_backup' => json_encode([]),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $internetServiceIds = [];
        foreach ($productIds as $code => $pid) {
            $internetServiceIds[$code] = DB::table('internet_services')->insertGetId([
                'products_id' => $pid,
                'routers_id' => $routerId,
                'profile' => $code,
                'rate_limit' => '50M/50M',
                'limit_at' => '25M/25M',
                'priority' => '8/8',
                'auto_soft_limit' => 7,
                'auto_suspend' => 14,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $customerId = DB::table('customers')->insertGetId([
            'code' => 'CST-001',
            'name' => 'Customer Demo',
            'id_card_number' => '3201xxxx',
            'address' => 'Jl. Demo No.1',
            'city' => 'Bekasi',
            'state' => 'Jawa Barat',
            'pos' => '17530',
            'group_area' => 'Area-1',
            'phone' => '628155555555',
            'email' => 'customer@example.com',
            'document_id_card' => null,
            'notes' => 'Demo customer',
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $subscriptionId = DB::table('subscriptions')->insertGetId([
            'customers_id' => $customerId,
            'products_id' => $productIds['PKT-BASIC'],
            'registration_date' => $now->toDateString(),
            'email_consent' => true,
            'sms_consent' => true,
            'whatsapp_consent' => true,
            'status' => 'Active',
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('provisionings')->insert([
            'subscriptions_id' => $subscriptionId,
            'routers_id' => $routerId,
            'device_brand' => 'TP-Link',
            'device_type_device_sn' => 'AX1800',
            'device_mac' => 'AA:BB:CC:DD:EE:FF',
            'device_conn' => 'PPPoE',
            'pppoe_name' => 'cst001',
            'pppoe_password' => 'secret',
            'activation_date' => $now->toDateString(),
            'technisian_name' => 'Tech One',
            'technisian_notes' => 'Installed OK',
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $invoiceId = DB::table('invoices')->insertGetId([
            'invoice_no' => 'INV-001',
            'customers_id' => $customerId,
            'subscriptions_id' => $subscriptionId,
            'products_id' => $productIds['PKT-BASIC'],
            'period_start' => $now->startOfMonth()->toDateString(),
            'period_end' => $now->endOfMonth()->toDateString(),
            'amount' => 200000,
            'tax_amount' => 20000,
            'discount_amount' => 0,
            'total_amount' => 220000,
            'due_date' => $now->addDays(10)->toDateString(),
            'status' => 'Unpaid',
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('payments')->insert([
            'invoices_id' => $invoiceId,
            'payment_method' => 'cash',
            'payment_gateway' => null,
            'transaction_id' => null,
            'amount' => 220000,
            'fee' => 0,
            'paid_at' => null,
            'reference_number' => 'REF-001',
            'document_proof' => null,
            'status' => 'pending',
            'notes' => null,
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $templateEmailId = DB::table('templates')->insertGetId([
            'name' => 'Reminder Email',
            'type' => 'email',
            'subject' => 'Tagihan Internet',
            'content' => 'Halo {{name}}, tagihan Anda jatuh tempo {{due_date}}.',
            'variables' => json_encode(['name', 'due_date', 'amount']),
            'is_active' => true,
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('templates')->insert([
            'name' => 'Reminder SMS',
            'type' => 'sms',
            'subject' => null,
            'content' => 'Tagihan {{invoice_no}} jatuh tempo {{due_date}}',
            'variables' => json_encode(['invoice_no', 'due_date']),
            'is_active' => true,
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('templates')->insert([
            'name' => 'Reminder WA',
            'type' => 'whatsapp',
            'subject' => null,
            'content' => 'Halo {{name}}, mohon bayar sebelum {{due_date}}',
            'variables' => json_encode(['name', 'due_date']),
            'is_active' => true,
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('reminders')->insert([
            'invoices_id' => $invoiceId,
            'templates_id' => $templateEmailId,
            'channel' => 'email',
            'trigger_type' => 'before_due',
            'days_offset' => 7,
            'scheduled_at' => $now->toDateTimeString(),
            'status' => 'pending',
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
