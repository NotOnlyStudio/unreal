<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Table;

class Setting extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $table = 'settings';

    public $timestamps = false;
}
