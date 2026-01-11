<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->json('permissions')->nullable();
            $table->timestamps();
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('alias')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pos', 20)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->json('bank_account')->nullable();
            $table->string('npwp')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('users_groups_id')->constrained('users_groups');
            $table->foreignId('companies_id')->nullable()->constrained('companies');
            $table->string('name');
            $table->string('password');
            $table->string('nik')->nullable();
            $table->string('photo')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->enum('locked', ['active', 'locked', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('companies_id')->constrained('companies');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->index();
            $table->string('name');
            $table->string('type');
            $table->string('description')->nullable();
            $table->string('market_segment')->nullable();
            $table->string('billing_cycle');
            $table->decimal('price', 16, 2);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->boolean('tax_included')->default(false);
            $table->timestamps();
        });

        Schema::create('promotions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('products_id')->constrained('products');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('discount', 8, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('routers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address');
            $table->integer('api_port')->default(8728);
            $table->integer('ssh_port')->default(22);
            $table->string('api_username');
            $table->string('api_password');
            $table->string('api_certificate')->nullable();
            $table->boolean('tls_enabled')->default(false);
            $table->boolean('ssh_enabled')->default(false);
            $table->string('status')->index()->default('offline');
            $table->integer('sync_interval')->default(300);
            $table->timestamp('last_sync_at')->nullable();
            $table->json('config_backup')->nullable();
            $table->timestamps();
        });

        Schema::create('internet_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('products_id')->constrained('products');
            $table->foreignId('routers_id')->constrained('routers');
            $table->string('profile')->nullable();
            $table->string('rate_limit')->nullable();
            $table->string('limit_at')->nullable();
            $table->string('priority')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('auto_soft_limit')->default(0);
            $table->integer('auto_suspend')->default(0);
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('id_card_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pos', 20)->nullable();
            $table->string('group_area')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('document_id_card')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('customers_id')->constrained('customers');
            $table->foreignId('products_id')->constrained('products');
            $table->date('registration_date')->nullable();
            $table->boolean('email_consent')->default(true);
            $table->boolean('sms_consent')->default(true);
            $table->boolean('whatsapp_consent')->default(true);
            $table->string('document_SF')->nullable();
            $table->string('document_ASF')->nullable();
            $table->string('document_PKS')->nullable();
            $table->string('status')->index()->default('Registered');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('provisionings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('subscriptions_id')->constrained('subscriptions');
            $table->foreignId('routers_id')->constrained('routers');
            $table->string('device_brand')->nullable();
            $table->string('device_type_device_sn')->nullable();
            $table->string('device_mac')->nullable();
            $table->string('device_conn')->default('PPPoE');
            $table->string('pppoe_name')->nullable();
            $table->string('pppoe_password')->nullable();
            $table->string('static_ip')->nullable();
            $table->string('static_gateway')->nullable();
            $table->date('activation_date')->nullable();
            $table->string('technisian_name')->nullable();
            $table->string('document_speedtest')->nullable();
            $table->text('technisian_notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no')->unique();
            $table->foreignId('customers_id')->constrained('customers');
            $table->foreignId('subscriptions_id')->constrained('subscriptions');
            $table->foreignId('products_id')->constrained('products');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->decimal('amount', 16, 2);
            $table->decimal('tax_amount', 16, 2)->default(0);
            $table->decimal('discount_amount', 16, 2)->default(0);
            $table->decimal('total_amount', 16, 2);
            $table->date('due_date')->nullable();
            $table->string('status')->index()->default('Unpaid');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('invoices_id')->constrained('invoices');
            $table->string('payment_method');
            $table->string('payment_gateway')->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 16, 2);
            $table->decimal('fee', 16, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('document_proof')->nullable();
            $table->string('status')->index()->default('pending');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ticket_number')->unique();
            $table->foreignId('customers_id')->constrained('customers');
            $table->foreignId('products_id')->nullable()->constrained('products');
            $table->string('caller_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('category')->index();
            $table->string('priority')->index()->default('medium');
            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('status')->index()->default('open');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('sla_due_date')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->integer('customer_rating')->nullable();
            $table->text('customer_feedback')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('users_id')->nullable();
            $table->string('users_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('action');
            $table->string('resource_type')->nullable();
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type'); // email, sms, whatsapp
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('reminders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('invoices_id')->constrained('invoices');
            $table->foreignId('templates_id')->constrained('templates');
            $table->string('channel'); // email, sms, whatsapp
            $table->string('trigger_type'); // before_due, on_due, after_due, pre_soft_limit, pre_suspend
            $table->integer('days_offset')->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->string('status')->index()->default('pending');
            $table->text('error_message')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('templates');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('provisionings');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('internet_services');
        Schema::dropIfExists('routers');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('products');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('users');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('users_groups');
    }
};
