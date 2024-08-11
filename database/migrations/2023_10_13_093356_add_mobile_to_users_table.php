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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile',15)->nullable()->default(NULL)->after('password');
            $table->string('image')->nullable()->default(NULL)->after('mobile');
            $table->string('gender',15)->nullable()->default(NULL)->after('image');
            $table->boolean('is_active')->default(true)->after('gender');
            $table->date('dob')->nullable()->default(NULL)->after('is_active');
            $table->json('hobbies')->nullable()->default(NULL)->after('dob');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mobile','image','gender','is_active','dob','hobbies']);
        });
    }
};
