<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

//    protected $table = 'member';
    protected $table = 'members';
//    protected $primaryKey = 'id_member';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
