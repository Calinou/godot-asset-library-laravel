<?php

use App\Asset;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->bigIncrements('asset_id');
            $table->string('title');
            $table->string('blurb')->nullable();
            $table->tinyInteger('category_id');
            $table->string('cost'); // SPDX license identifier
            $table->tinyInteger('support_level_id')->default(Asset::SUPPORT_LEVEL_COMMUNITY);
            $table->text('description');
            $table->text('browse_url');
            $table->text('icon_url')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('modify_date');

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
        Schema::dropIfExists('assets');
    }
}
