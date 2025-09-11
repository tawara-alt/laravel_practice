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
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id'); // 符号なし（負の値を持たない整数）の大きな整数型でidカラムのすぐ後
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
        }); // foreignで外部キー制約を設定してreferencesで参照先はusersテーブルのidカラムonで参照するテーブルの指定。
           //カスケード削除で親テーブルのレコードが削除されたときに、それに紐づく子テーブルのレコードも自動的に削除される。
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['user_id']); //外部キー制約を削除。外部キー制約があると、そのカラムを削除できないため、先に制約を外す必要がある。
            $table->dropColumn('user_id'); //カラム自体を削除
        });
    }
};
