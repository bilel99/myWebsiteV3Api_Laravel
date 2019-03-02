<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogMedia extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'blog_media';
    protected $guarded = [];
    public $timestamps = false;
}
