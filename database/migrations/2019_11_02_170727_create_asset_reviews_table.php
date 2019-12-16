<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table
                ->boolean('is_positive')
                ->comment('1: Positive review, 0: Negative review');
            $table
                ->text('comment')
                ->nullable()
                ->comment('Comment in Markdown format');
            $table
                ->text('html_comment')
                ->nullable()
                ->comment('Comment rendered as HTML (cached for performance)');
            $table->timestamps();

            $table->unsignedBigInteger('asset_id');
            $table->foreign('asset_id')->references('asset_id')->on('assets');

            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_reviews');
    }
}
