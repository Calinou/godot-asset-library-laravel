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
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('author');
            $table->tinyInteger('category');
            $table->string('cost'); // SPDX license identifier
            $table->string('godot_version');
            $table->tinyInteger('support_level')->default(Asset::SUPPORT_LEVEL_COMMUNITY);
            $table->text('description');
            $table->text('browse_url');
            $table->text('download_url');
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
        Schema::dropIfExists('assets');
    }
}
