<?php

use App\Enums\CustomerTributes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->after('email', function(Blueprint $table){
                $table->enum('tribute', CustomerTributes::getCases())->default(CustomerTributes::NOT_RESPONSIBLE->value);
            });
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
           $table->dropColumn('tribute');
        });
    }
};
