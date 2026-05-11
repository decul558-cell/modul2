<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->foreignId('toko_id')->after('id')->constrained('tokos')->onDelete('cascade');
            $table->foreignId('user_id')->after('toko_id')->constrained('users')->onDelete('cascade');
            $table->decimal('latitude_sales', 10, 7)->after('user_id');
            $table->decimal('longitude_sales', 10, 7)->after('latitude_sales');
            $table->decimal('accuracy_sales', 8, 2)->after('longitude_sales');
            $table->decimal('jarak_meter', 8, 2)->after('accuracy_sales');
            $table->enum('status', ['diterima', 'ditolak'])->after('jarak_meter');
        });
    }

    public function down(): void
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['toko_id', 'user_id', 'latitude_sales', 'longitude_sales', 'accuracy_sales', 'jarak_meter', 'status']);
        });
    }
};