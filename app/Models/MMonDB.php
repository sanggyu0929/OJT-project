<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MMonDB extends Model
{
    use HasFactory;
    protected $table = "admins";
    protected $fillable = ["email", "pw", "name"];
    public $timestamps = false;
}
