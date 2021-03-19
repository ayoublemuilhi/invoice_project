<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\InvoiceAttachement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Session;
class ArchiveController extends Controller
{

    public function index()
    {
        $invoices = Invoice::with('section')->onlyTrashed()->get();

        return view('invoices.Archives_invoices',compact('invoices'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request)
    {
        // نقل الى الفواتير

        $invoices = Invoice::onlyTrashed()->where('id',$request->invoice_id)->restore();
        Session::flash('success','تم استرجاع الفاتورة بنجاح');
        return redirect()->route('archives.index');
    }

    public function destroy(Request $request)
    {
        // حدف  الفاتورة نهائيا
        $invoices = Invoice::onlyTrashed()->where('id',$request->invoice_id)->first();
        $attchements = InvoiceAttachement::where('id',$request->invoice_id)->first();
            if(!empty($attchements->invoice_number)){
                Storage::disk('public_uploads')->delete($attchements->file_name);
            }
        $invoices->forceDelete();
        Session::flash('success','تم حدف الفاتورة بنجاح');
        return redirect()->route('archives.index');
    }
}
