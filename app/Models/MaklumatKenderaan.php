<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaklumatKenderaan extends Model
{
    use HasFactory;

    protected $table = 'maklumat_kenderaan';
    protected $guarded = ['id'];
}
