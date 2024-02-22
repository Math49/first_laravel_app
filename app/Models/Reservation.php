<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'date_start', 'date_end', 'chambre_id'];


    public function chambre()
    {
        return $this->belongsTo(Chambres::class);
    }
}
