<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['content'];
    /**
     * 关联 Status n:1 User 关系
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
