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
                        $table->json('partner')->nullable()->after('documentation')->comment('Partner link and label');
           
            $table->boolean('is_sustainable')->default(false)->after('output_types');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
              $table->dropColumn([
                'partner',
                'is_sustainable'
            ]);
        });
    }
};
