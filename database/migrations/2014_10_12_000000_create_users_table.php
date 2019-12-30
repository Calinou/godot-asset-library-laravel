<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->unique();
            $table->string('full_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable()->comment('Can be null if using OAuth2 only');
            $table->string('provider')->nullable()->comment('The OAuth2 provider name (if any)');
            $table->string('provider_id')->nullable()->comment('The OAuth2 provider unique identifier (may be used if the provider supports other means of logging in than an email)');
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_blocked')->default(false)->comment("If `true`, the user can't post/edit anything but their existing content is still visible");
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
