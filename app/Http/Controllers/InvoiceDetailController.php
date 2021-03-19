<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\InvoiceAttachement;
use App\InvoiceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Session;
class InvoiceDetailController extends Controller
{


    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($invoiceDetail)
    {

        $invoices = Invoice::where('id',$invoiceDetail)->first();


        $details = $invoices->invoiceDetail->where('invoice_id',$invoiceDetail)->cursor();

        $total = InvoiceAttachement::where('invoice_id',$invoiceDetail)->count();//has file in attachment or no

        if($total > 0){
            $attachments = $invoices->invoiceAttachement->where('invoice_id',$invoiceDetail)->cursor();

            return view('invoices.details_invoices',compact('invoices','details','attachments','total'));

        }else{
            return view('invoices.details_invoices',compact('invoices','details','total'));
        }


    }
    public function open_file($file_name){
            $files = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($file_name);
            return response()->file($files);
    }
    public function get_file($file_name){
        $files = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($file_name);
        return response()->download($files);
    }

    public function edit(InvoiceDetail $invoiceDetail)
    {

    }


    public function update(Request $request, InvoiceDetail $invoiceDetail)
    {
        //
    }


    public function destroy(Request $request)
    {

        $invoice = InvoiceAttachement::select('id','file_name')->find($request->id_file);

        //supprimer la photo en DB
         $invoice->delete();
         //supprimer la photo en dossier
        Storage::disk('public_uploads')->delete($invoice->file_name);
        Session::flash('success','تم حدف المرفق بنجاح');
        return redirect()->back();
    }
}
