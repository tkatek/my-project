<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('contact_method')->nullable()->after('phone');
            $table->string('hotel')->nullable()->after('contact_method');
            $table->boolean('consent')->default(false)->after('hotel');
            $table->string('request_id')->nullable()->unique()->after('consent');
            $table->unsignedBigInteger('unit_price')->nullable()->after('notes');

            $table->foreign('package_id')->references('id')->on('packages')->nullOnDelete();
            $table->index('status');
            $table->date('visit_date')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropIndex(['status']);
            $table->dropColumn(['contact_method', 'hotel', 'consent', 'request_id', 'unit_price']);
            $table->date('visit_date')->nullable()->change();
        });
    }
};
