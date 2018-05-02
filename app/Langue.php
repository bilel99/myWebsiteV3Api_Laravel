<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Langue extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'langue';
    protected $guarded = [];

    /**
     * @return bool|string
     */
    public function getCreateddateAttribute(){
        return date('d/m/Y H\Hi', date_timestamp_get(date_create($this->created_at)));
    }
}
