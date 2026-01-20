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
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('email')->nullable()->after('event_date');
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'client_password']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('client_password')->nullable();
        });
    }
};
