<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add meta fields to page_contents (terms, privacy, shipping + new pages)
        Schema::table('page_contents', function (Blueprint $table) {
            if (!Schema::hasColumn('page_contents', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('title');
            }
            if (!Schema::hasColumn('page_contents', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });

        // Add meta fields to about_page_contents + logo
        Schema::table('about_page_contents', function (Blueprint $table) {
            if (!Schema::hasColumn('about_page_contents', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('id');
            }
            if (!Schema::hasColumn('about_page_contents', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('about_page_contents', 'logo')) {
                $table->string('logo')->nullable()->after('meta_description');
            }
        });

        // Add meta fields to contact_page_contents
        Schema::table('contact_page_contents', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_page_contents', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('id');
            }
            if (!Schema::hasColumn('contact_page_contents', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('page_contents', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });
        Schema::table('about_page_contents', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'logo']);
        });
        Schema::table('contact_page_contents', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });
    }
};
