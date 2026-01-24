<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audits', function (Blueprint $table) {
        $table->unsignedBigInteger('parent_id')->nullable()->after('auditable_id');
        $table->string('parent_type')->nullable()->after('parent_id');

        $table->index(['parent_type', 'parent_id']);
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audits', function (Blueprint $table) {
        $table->dropIndex(['parent_type', 'parent_id']);
        $table->dropColumn(['parent_id', 'parent_type']);
    });
    }
};
