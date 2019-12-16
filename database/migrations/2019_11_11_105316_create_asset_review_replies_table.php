<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetReviewRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_review_replies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table
                ->text('comment')
                ->nullable()
                ->comment('Reply by the asset author (in Markdown format)');
            $table
                ->text('html_comment')
                ->nullable()
                ->comment('Reply by the asset author (rendered as HTML)');
            $table->timestamps();

            $table->unsignedBigInteger('asset_review_id')->unique();
            $table->foreign('asset_review_id')->references('id')->on('asset_reviews')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_review_replies');
    }
}
