<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'setting';
//    protected $table = 'settings';
    protected $primaryKey = 'id_setting';
//    protected $primaryKey = 'id';
    protected $guarded = [];
}
