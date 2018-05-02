<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PortfolioMedia extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'portfolio_media';
    protected $guarded = [];
    public $timestamps = false;
}
