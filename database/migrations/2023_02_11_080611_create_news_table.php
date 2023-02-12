<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string("news_title");
            $table->unsignedBigInteger("news_category_id");
            $table->date("news_publication_date");
            $table->text("news_web_url");
            $table->string("news_source_data");
            $table->text("news_image_url");
            $table->foreign("news_category_id")->references("id")->on("news_categories");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
};
