<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade')->after('id');
            $table->string('barang_id')->after('order_id');
            $table->integer('quantity')->after('barang_id');
            $table->decimal('price', 15, 2)->after('quantity');
            $table->decimal('subtotal', 15, 2)->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['order_id', 'barang_id', 'quantity', 'price', 'subtotal']);
        });
    }
};
