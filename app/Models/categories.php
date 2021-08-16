<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
    use HasFactory;
    protected $table = "categories";
    protected $fillable = ["name", "used"];
    public $timestamps = false;

    public function getUsedAttribute($value) {
        if ($value === '1') {
            return $value = '사용';
        } else {
            return $value = '미사용';
        }
    }
}
