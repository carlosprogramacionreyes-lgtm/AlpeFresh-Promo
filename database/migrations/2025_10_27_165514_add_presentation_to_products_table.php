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
        if (! Schema::hasColumn('products', 'presentation')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('presentation')->nullable()->after('category');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'presentation')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('presentation');
            });
        }
    }
};
