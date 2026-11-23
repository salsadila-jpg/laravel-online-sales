<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_services', function (Blueprint $table) {
            $table->string('courier', 80);
            $table->string('service', 80);
            $table->string('eta', 80)->nullable();
            $table->decimal('cost', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('shipping_services', function (Blueprint $table) {
            $table->dropColumn([
                'courier',
                'service',
                'eta',
                'cost',
                'is_active',
            ]);
        });
    }
};
