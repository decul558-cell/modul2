<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_code')->unique()->after('id');
            $table->foreignId('customer_id')->constrained('customers')->after('order_code');
            $table->decimal('total_amount', 15, 2)->default(0)->after('customer_id');
            $table->string('payment_status')->default('pending')->after('total_amount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['order_code', 'customer_id', 'total_amount', 'payment_status']);
        });
    }
};
