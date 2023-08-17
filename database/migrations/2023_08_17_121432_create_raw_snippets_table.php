<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_snippets', function (Blueprint $table) {
            $table->id();
            $table->text('instruction')->nullable();
            $table->text('raw');
            $table->unsignedBigInteger('code_snippet_id');
            $table->integer('order')->nullable();
            $table->foreign('code_snippet_id')->references('id')->on('code_snippets')->onDelete('cascade');
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
        Schema::table('raw_snippets', function (Blueprint $table) {
            $table->dropForeign(['code_snippet_id']);
            $table->dropColumn('code_snippet_id');
        });
    }
};
