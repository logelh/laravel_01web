<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Authenticatable 是授权相关功能的引用。
class User extends Authenticatable
{
    //Notifiable 是消息通知相关功能引用
    //HasFactory 是模型工厂相关功能的引用
    use HasFactory, Notifiable;

    //需要在 Eloquent 模型中借助对 table 属性的定义，来指明要进行数据库交互的数据库表名称
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     * 添加了 fillable 在过滤用户提交的字段，只有包含在该属性中的字段才能够被正常更新
     * 防止前端提交的数据带有指向性的修改
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * 当我们需要对用户密码或其它敏感信息在用户实例通过数组或 JSON 显示时进行隐藏，则可使用 hidden 属性
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }
}
