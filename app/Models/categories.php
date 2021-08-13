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

    // public function setUsedAttribute($value) {
    //     if($this->attributes['used'] == '1') {
    //         $this->attributes['used'] = '사용';
    //     } else {
    //         $this->attributes['used'] = '미사용';
    //     }
        
    // }
}
