<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CvCompetenceLangue extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'cv_competence_langue';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * @return bool|string
     */
    public function getCreateddateAttribute(){
        return date('d/m/Y H\Hi', date_timestamp_get(date_create($this->created_at)));
    }
}
