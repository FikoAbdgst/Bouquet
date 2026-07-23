<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->string('orderer_name');
            $table->string('orderer_phone');
            $table->date('needed_date');
            $table->enum('pickup_method', ['self_pickup', 'delivery']);
            $table->text('delivery_address')->nullable();
            $table->text('special_note')->nullable();
            $table->unsignedInteger('total_price');
            $table->string('payment_proof_url');
            $table->boolean('payment_verified')->default(false);
            $table->enum('status', [
                'menunggu_konfirmasi',
                'dikonfirmasi',
                'diproses',
                'dikirim',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_konfirmasi');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
