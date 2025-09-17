<?php

use App\Enums\LegalOrganization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->after('tribute', function(Blueprint $table){
                $table->enum('legal_organization', LegalOrganization::getCases())->default(LegalOrganization::NATURAL_PERSON->value);
            });
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('legal_organization');
        });
    }
};
