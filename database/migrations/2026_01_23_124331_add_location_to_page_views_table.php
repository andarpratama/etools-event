<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->string('country')->nullable()->after('user_agent');
            $table->string('city')->nullable()->after('country');
            $table->string('region')->nullable()->after('city');
            $table->string('country_code', 2)->nullable()->after('region');
            $table->decimal('latitude', 10, 8)->nullable()->after('country_code');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->dropColumn(['country', 'city', 'region', 'country_code', 'latitude', 'longitude']);
        });
    }
};
