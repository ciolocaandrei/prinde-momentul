<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Wedding;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('access_code', 32)->unique()->nullable()->after('upload_code');
        });

        // Generate access codes for existing weddings
        Wedding::whereNull('access_code')->each(function ($wedding) {
            $wedding->update([
                'access_code' => $this->generateUniqueCode(),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn('access_code');
        });
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = Str::random(24);
        } while (Wedding::where('access_code', $code)->exists());

        return $code;
    }
};
