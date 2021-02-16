<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name',50)->unique();
            $table->text('description')->nullable();
            $table->unsignedMediumInteger('hierarchy')->unique();
            // Users related
            $table->boolean('create_users')->default(false);
            $table->boolean('read_users')->default(false);
            $table->boolean('update_users')->default(false);
            $table->boolean('delete_users')->default(false);
            // Post related
            $table->boolean('create_post')->default(false);
            $table->boolean('update_elses_post')->default(false);
            $table->boolean('delete_elses_post')->default(false);
            // Comments related
            $table->boolean('update_elses_comments')->default(false);
            $table->boolean('delete_elses_comments')->default(false);

            // Roles related
            $table->boolean('create_roles')->default(false);
            $table->boolean('read_roles')->default(false);
            $table->boolean('update_roles')->default(false);
            $table->boolean('delete_roles')->default(false);
            
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
        Schema::dropIfExists('roles');
    }
}
