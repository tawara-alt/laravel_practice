<?php

namespace App\Models;
//この機能を使うよ～という宣言
use Illuminate\Database\Eloquent\Model; //LaravelのEloquent ORMを使って tasks テーブルのデータを操作しますよ
use Illuminate\Database\Eloquent\SoftDeletes; //削除されたデータも deleted_at に記録して、あとから復元できるようにしますよ

class Task extends Model
{
    use SoftDeletes;

    //ユーザが入力するカラムを一括代入に含める。あとの4つはlaravelが自動で扱う。
    protected $fillable = [
        'title',
        'content',
        'deadline_at',
        'support_at',
        'priority',
        'status',
        'user_id',
    ];
    //taksテーブルのuser_idカラムがusersテーブルのidを参照していることを前提に、Eloquentが自動的に関連するユーザー情報を取得。
    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    //日時として扱いたいカラムを指定。Carbonインスタンスに変換。
    protected $dates = ['deleted_at', 'deadline_at', 'support_at'];
}