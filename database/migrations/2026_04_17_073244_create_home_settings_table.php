<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_label')->default('Kiệt tác di sản');
            $table->string('hero_title_line1')->default('Tinh Hoa');
            $table->string('hero_title_line2')->default('Trang Sức');
            $table->text('hero_description')->nullable();
            $table->string('hero_btn_primary_text')->default('Khám phá tuyệt tác');
            $table->string('hero_btn_secondary_text')->default('Xem bộ sưu tập');
            $table->string('featured_title')->default('Sản phẩm nổi bật');
            $table->string('featured_subtitle')->default('Những thiết kế được yêu thích nhất');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_settings');
    }
};
