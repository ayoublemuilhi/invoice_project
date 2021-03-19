<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceAttachement extends Model
{
    protected $guarded = [];
    ####################### Start relation
    public function invoice(){
        return $this->hasOne(Invoice::class,'invoice_id','id');
    }
    ####################### End relation
}
