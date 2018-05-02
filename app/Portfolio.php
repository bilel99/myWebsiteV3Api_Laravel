<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'portfolio';
    protected $guarded = [];

    public function media(){
        return $this->belongsToMany('App\Media', 'portfolio_media', 'portfolio_id', 'media_id');
    }

    /**
     * @return bool|string
     */
    public function getCreateddateAttribute(){
        return date('d/m/Y H\Hi', date_timestamp_get(date_create($this->created_at)));
    }
}
