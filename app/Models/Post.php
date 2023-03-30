<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $guarded = [];

    public function post_images(){
        return $this->hasMany('App\Models\PostImage', 'pid','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(PostComment::class, 'pid')->where('pcid', 0)->orderBy('created_at', 'desc');;
    }
}