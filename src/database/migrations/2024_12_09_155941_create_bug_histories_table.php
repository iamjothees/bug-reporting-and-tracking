<?php

use App\Models\Bug;
use App\Models\User;
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
        Schema::create('bug_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Bug::class, 'bug_id');
            $table->string('old_status');
            $table->string('new_status');
            $table->foreignIdFor(User::class, 'updater_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bug_histories');
    }
};