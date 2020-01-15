<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Projects extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'projects';

    protected $fillable = [
    	'name', 'finished', 'created_by',
    ];
}
