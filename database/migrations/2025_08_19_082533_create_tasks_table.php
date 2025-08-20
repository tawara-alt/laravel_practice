<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); //自動連番で作ってね
            $table->string('title'); 
            $table->text('content'); 
            $table->dateTime('deadline_at'); 
            $table->dateTime('support_at')->nullable(); 
            $table->integer('priority'); 
            $table->integer('status'); 
            $table->timestamps(); //created_atとupdated_atのカラム
            $table->softDeletes(); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
