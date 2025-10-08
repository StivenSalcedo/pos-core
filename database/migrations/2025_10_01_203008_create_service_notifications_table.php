<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('channel'); // 'sms', 'email', 'whatsapp'
            $table->string('destination')->nullable(); // phone or email
            $table->text('message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->string('status')->default('pending'); // pending, sent, failed
            $table->text('meta')->nullable(); // json meta (response ids)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_notifications');
    }
};
