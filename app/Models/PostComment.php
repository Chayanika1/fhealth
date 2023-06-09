<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $table = 'post_comments';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
    public function replies()
    {
        return $this->hasMany(PostComment::class, 'pcid');
    }
    
}