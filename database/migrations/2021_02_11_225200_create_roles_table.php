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

            $permissions = array_merge(config('roles.permissions.core'), config('roles.permissions.extended'));
            foreach ($permissions as $permission){
                $table->boolean($permission)->default(false);
            }
            
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
