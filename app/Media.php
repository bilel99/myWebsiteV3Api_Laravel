<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'media';
    protected $guarded = [];

    public function portfolio(){
        return $this->belongsToMany('App\Portfolio', 'portfolio_media', 'portfolio_id', 'media_id');
    }

    /**
     * @return bool|string
     */
    public function getCreateddateAttribute(){
        return date('d/m/Y H\Hi', date_timestamp_get(date_create($this->created_at)));
    }
}
