<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Tasks extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'tasks';

    protected $fillable = [
    	'project_id', 'keterangan', 'finished', 'develop_by', 'created_by',
    ];
}
