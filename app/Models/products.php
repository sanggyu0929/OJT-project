<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\products;
use Illuminate\Database\Eloquent\SoftDeletes;

class products extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "products";
    protected $primaryKey = 'Pidx';
    protected $fillable = ["name", "selectedBrand", "selectedCategories", "state", "price", "sales", "extension"];
    public $timestamps = false;

    public function getStateAttribute($value) {
        if ($value == '0') {
            return $value = '판매중';
        } else if ($value == '1') {
            return $value = '일시품절';
        } else if ($value == '2') {
            return $value = '품절';
        } else if ($value == '3') {
            return $value = '판매중지';
        }
    }
    public function getCreatedAtAttribute($date)
    {
        $date = explode(' ', $date);
        return $date[0];
    }
}
