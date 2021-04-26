<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// php artisan make:model Article -m
// 创建文章model 带-m 也会创建migrations 文件
class Article extends Model
{
    use HasFactory;
    // 如果你需要指定自己的数据表，则可以通过 table 属性来定义
    // protected $table = 'my_articles';
    // 否则默认Article 数据模型类对应 articles 表；User 数据模型类对应 users 表；BlogPost 数据模型类对应 blog_posts 表；使用「下划线命名法」与「复数形式名称」
}
