<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Fields needed for the 2026-27 company profile content. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('client')->nullable()->after('location');
            $table->string('year', 40)->nullable()->after('client');
            $table->string('contract_value', 40)->nullable()->after('year');
            $table->string('role', 160)->nullable()->after('contract_value');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->text('summary')->nullable()->after('title');
            $table->json('points')->nullable()->after('summary');
        });

        // The tables of completed / ongoing work listed in the profile.
        Schema::create('track_records', function (Blueprint $table) {
            $table->id();
            $table->string('section', 40)->index();
            $table->string('title');
            $table->string('client')->nullable();
            $table->string('location')->nullable();
            $table->text('scope')->nullable();
            $table->string('year', 40)->nullable();
            $table->string('contract_value', 40)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('track_records');

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['summary', 'points']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['client', 'year', 'contract_value', 'role']);
        });
    }
};
