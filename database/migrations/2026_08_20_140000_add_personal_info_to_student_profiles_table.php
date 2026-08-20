<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Contact number reuses the existing users.phone column rather than
     * adding a duplicate - only email/address/parent/guardian are new here.
     */
    public function up(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('personal_email')->nullable()->after('user_id');
            $table->text('address')->nullable()->after('personal_email');
            $table->string('parent_name')->nullable()->after('address');
            $table->string('parent_contact')->nullable()->after('parent_name');
            $table->string('guardian_name')->nullable()->after('parent_contact');
            $table->string('guardian_contact')->nullable()->after('guardian_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'personal_email',
                'address',
                'parent_name',
                'parent_contact',
                'guardian_name',
                'guardian_contact',
            ]);
        });
    }
};
