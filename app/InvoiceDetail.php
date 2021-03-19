<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
   protected $guarded = [];

####################### Start relation
    public function invoice(){
        return $this->belongsTo(Invoice::class,'invoice_id','id');
    }
    ####################### End relation
}
