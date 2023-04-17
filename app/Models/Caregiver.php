<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caregiver extends Model
{
    use HasFactory;
    protected $fillable=[
        'Role','User_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'User_id');
    }
    public function patients()
    {
        return $this->belongsToMany(Patient::class,'caregivers_paients');
    }
    public $timestamps =false;
}
