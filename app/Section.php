<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
      'section_name','description','Created_by','created_at','updated_at'
    ];


    ############# Relation ##################
    public function  product(){
        return $this->hasMany(Product::class,'section_id','id');
    }
    public function  invoice(){
        return $this->hasMany(Invoice::class,'section_id','id');
    }

}
