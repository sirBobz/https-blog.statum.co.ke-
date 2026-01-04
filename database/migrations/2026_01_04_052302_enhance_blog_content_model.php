<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->text('description')->nullable()->after('slug');
            $table->string('color')->nullable()->after('description');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete()->after('id');
            $table->foreignId('author_id')->nullable()->constrained()->nullOnDelete()->after('category_id');
            $table->integer('reading_time')->nullable()->after('body');
            $table->string('meta_title')->nullable()->after('status');
            $table->string('og_image')->nullable()->after('meta_title');
        });

        Schema::dropIfExists('category_post');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('category_post', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->dropForeign(['author_id']);
            $table->dropColumn('author_id');
            $table->dropColumn('reading_time');
            $table->dropColumn('meta_title');
            $table->dropColumn('og_image');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['description', 'color']);
        });
    }
};
