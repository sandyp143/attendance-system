<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer( "organization_id" )->index()->unsigned();
            $table->foreign("organization_id")->references( "id" )->on( "organizations" ) ->onUpdate("cascade")->onDelete("cascade");
            $table->integer( "user_id" )->index()->unsigned();
            $table->foreign("user_id")->references( "id" )->on( "users" ) ->onUpdate("cascade")->onDelete("cascade");
            $table->string('role');
            
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
        Schema::drop('members');
    }
}
