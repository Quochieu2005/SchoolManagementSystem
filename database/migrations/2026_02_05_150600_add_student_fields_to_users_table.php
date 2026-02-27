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

            if (!Schema::hasColumn('users', 'admission_number')) {
                $table->string('admission_number')->nullable();
            }

            if (!Schema::hasColumn('users', 'roll_number')) {
                $table->string('roll_number')->nullable();
            }

            if (!Schema::hasColumn('users', 'class_id')) {
                $table->unsignedBigInteger('class_id')->nullable();
            }

            if (!Schema::hasColumn('users', 'caste')) {
                $table->string('caste')->nullable();
            }

            if (!Schema::hasColumn('users', 'religion')) {
                $table->string('religion')->nullable();
            }

            if (!Schema::hasColumn('users', 'admission_date')) {
                $table->date('admission_date')->nullable();
            }

            if (!Schema::hasColumn('users', 'blood_group')) {
                $table->string('blood_group')->nullable();
            }

            if (!Schema::hasColumn('users', 'height')) {
                $table->string('height')->nullable();
            }

            if (!Schema::hasColumn('users', 'weight')) {
                $table->string('weight')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
