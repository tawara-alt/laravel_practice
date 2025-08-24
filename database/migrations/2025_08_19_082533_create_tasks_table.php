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
            $table->string('title',100); //タスクのタイトル
            $table->text('content'); //タスクの詳細内容
            $table->dateTime('deadline_at'); //タスクの締め切り日時
            $table->dateTime('support_at')->nullable(); // サポートがない場合は空の値を保存。未入力でもいいよ。
            $table->unsignedTinyInteger('priority'); //数字が入る
            $table->unsignedTinyInteger('status'); //数字が入る
            $table->timestamps(); //created_atとupdated_atのカラム
            $table->softDeletes(); //論理削除

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
