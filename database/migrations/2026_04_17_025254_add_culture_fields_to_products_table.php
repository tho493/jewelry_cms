<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_hantu')->nullable()->after('name');
            $table->string('main_character')->nullable()->after('name_hantu');
            $table->text('form_characteristics')->nullable()->after('description');
            $table->text('cultural_meaning')->nullable()->after('form_characteristics');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'name_hantu',
                'main_character',
                'form_characteristics',
                'cultural_meaning',
            ]);
        });
    }
};
