<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cgu extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'cgu';
    protected $guarded = [];

    /**
     * @return bool|string
     */
    public function getCreateddateAttribute(){
        return date('d/m/Y H\Hi', date_timestamp_get(date_create($this->created_at)));
    }
}
