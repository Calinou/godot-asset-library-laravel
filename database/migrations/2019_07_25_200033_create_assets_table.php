<?php

declare(strict_types=1);

use App\Models\Asset;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table
                ->string('cost')
                ->comment('SPDX license identifier');
            $table
                ->tinyInteger('support_level_id')
                ->default(Asset::SUPPORT_LEVEL_COMMUNITY)
                ->comment(
                    Asset::SUPPORT_LEVEL_TESTING.': Testing, '
                        .Asset::SUPPORT_LEVEL_COMMUNITY.': Community, '
                        .Asset::SUPPORT_LEVEL_OFFICIAL.': Official'
                );
            $table
                ->text('description')
                ->comment('Description in Markdown format');
            $table
                ->text('html_description')
                ->comment('Description rendered as HTML (cached for performance)');
            $table->text('tags')->nullable();
            $table->text('browse_url');
            $table->text('issues_url')->nullable();
            $table->text('changelog_url')->nullable();
            $table->text('donate_url')->nullable();
            $table->text('icon_url')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('modify_date');
            $table
                ->boolean('is_published')
                ->default(true)
                ->comment('If `false`, the asset will be hidden from the list and search results. It also will be only visible by its author');
            $table
                ->boolean('is_archived')
                ->default(false)
                ->comment("If `true`, the asset will be marked as archived. This can be added voluntarily by the author to indicate the asset won't receive further updates");
            $table->integer('score')->default(0)->comment('Calculated from reviews (+1 for positive, -1 for negative)');

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
