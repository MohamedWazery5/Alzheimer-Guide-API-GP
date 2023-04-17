<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepeatType extends Model
{
    use HasFactory;
    protected $table='repeats_type';
    protected $fillable=['type'];
}
