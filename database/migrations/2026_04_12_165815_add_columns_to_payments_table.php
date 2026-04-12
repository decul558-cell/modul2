<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade')->after('id');
            $table->string('midtrans_order_id')->nullable()->after('order_id');
            $table->decimal('amount', 15, 2)->after('midtrans_order_id');
            $table->string('status')->default('pending')->after('amount');
            $table->string('transaction_id')->nullable()->after('status');
            $table->string('payment_type')->nullable()->after('transaction_id');
            $table->json('midtrans_response')->nullable()->after('payment_type');
            $table->timestamp('paid_at')->nullable()->after('midtrans_response');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['order_id', 'midtrans_order_id', 'amount', 'status', 'transaction_id', 'payment_type', 'midtrans_response', 'paid_at']);
        });
    }
};
