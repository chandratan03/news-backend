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
        Schema::create('news_contributors', function (Blueprint $table) {
            $table->unsignedBigInteger("contributor_id");
            $table->unsignedBigInteger("news_id");
            $table->primary(['contributor_id', 'news_id']);
            $table->foreign("contributor_id")->references("id")->on("contributors");
            $table->foreign("news_id")->references("id")->on("news");
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
        Schema::dropIfExists('news_contributors');
    }
};
