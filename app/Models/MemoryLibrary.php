<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemoryLibrary extends Model
{
    use HasFactory;
    protected $table = 'memories_library';
    protected $fillable = [
        'name', 'type',"photo" ,'patient_id', 'description'
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
