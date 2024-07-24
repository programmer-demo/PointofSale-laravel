<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    use HasFactory;
    protected $table = 'sells';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id');
    }
}
