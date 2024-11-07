<?php

use App\Enums\LeadStatus;
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
        Schema::table('leads', function (Blueprint $table) {
            // $table->string('status')->after('description')->default(LeadStatus::Pending);
            $table->enum('status', [
                LeadStatus::Pending->value,
                LeadStatus::Approved->value,
                LeadStatus::Canceled->value
            ])->after('description')->default(LeadStatus::Pending->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
