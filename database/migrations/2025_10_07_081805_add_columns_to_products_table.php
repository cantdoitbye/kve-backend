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
              $table->string('sku')->nullable()->after('title');
            $table->json('service_info')->nullable()->after('specifications');
            $table->json('included')->nullable()->after('service_info');
            $table->json('documentation')->nullable()->after('included');
            $table->json('input_types')->nullable()->after('documentation');
            $table->json('output_types')->nullable()->after('input_types');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
             $table->dropColumn([
                'sku',
                'service_info',
                'included',
                'documentation',
                'input_types',
                'output_types'
            ]);
        });
    }
};
