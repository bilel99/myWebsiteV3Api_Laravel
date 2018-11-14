<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'cv';
    protected $guarded = [];

    public function media(){
        return $this->belongsTo('\App\Media', 'media_id');
    }

    public function user(){
        return $this->belongsTo('\App\User', 'user_id');
    }

    public function formation(){
        return $this->belongsToMany('App\Formation', 'cv_formation', 'cv_id', 'formation_id');
    }

    public function competence(){
        return $this->belongsToMany('App\Competence', 'cv_competence', 'cv_id', 'competence_id');
    }

    public function experience(){
        return $this->belongsToMany('App\Experience', 'cv_experience', 'cv_id', 'experience_id');
    }

    public function competenceLangue(){
        return $this->belongsToMany('App\CompetenceLangue', 'cv_competence_langue', 'cv_id', 'competence_langue_id');
    }

    public function loisir(){
        return $this->belongsToMany('App\Loisir', 'cv_loisir', 'cv_id', 'loisir_id');
    }

    /**
     * @return bool|string
     */
    public function getCreateddateAttribute(){
        return date('d/m/Y H\Hi', date_timestamp_get(date_create($this->created_at)));
    }
}
