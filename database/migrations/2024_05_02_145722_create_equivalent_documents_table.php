<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equivalent_documents', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->text('image_qr');
            $table->string('cude');
            $table->json('numbering_range');
            $table->unsignedBigInteger('equivalent_documentable_id');
            $table->string('equivalent_documentable_type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equivalent_documents');
    }
};
