<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_versions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('version_string');
            $table->string('godot_version');
            $table->string('download_url')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('modify_date');

            $table->unsignedBigInteger('asset_id');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_versions');
    }
}
