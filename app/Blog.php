<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = "blog";
    protected $guarded = [];

    /**
     * Relation many to many table Pivot
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function media(){
        return $this->belongsToMany('App\Media', 'blog_media', 'blog_id', 'media_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users(){
        return $this->belongsTo('\App\User', 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function langue(){
        return $this->belongsTo('\App\Langue', 'langue_id');
    }

    /**
     * @return bool|string
     */
    public function getCreateddateAttribute()
    {
        return date('d/m/Y H\Hi', date_timestamp_get(date_create($this->created_at)));
    }
}
