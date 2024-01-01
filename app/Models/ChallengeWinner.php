<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeWinner extends Model
{
    protected $table = "challenge_winners";
    use HasFactory;
    protected $guarded = [];

}
