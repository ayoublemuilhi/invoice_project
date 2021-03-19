<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'Product_name','description','section_id','created_at','updated_at'
    ];


    ############# Relation ##################
    public function  section(){
        return $this->belongsTo(Section::class,'section_id','id');
    }
}
