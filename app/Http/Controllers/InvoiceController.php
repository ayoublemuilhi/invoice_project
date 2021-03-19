<?php

namespace App\Http\Controllers;

use App\Exports\invoicesExport;
use App\Invoice;
use App\InvoiceAttachement;
use App\InvoiceDetail;
use App\Notifications\AddInvoice;
use App\Product;
use App\Section;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Session;
class InvoiceController extends Controller
{
    use ImageUpload;

    public function export()
    {
        return Excel::download(new invoicesExport, 'invoices.xlsx');
    }



    public function index()
    {
        $invoices = Invoice::with('section')->get();

        return view('invoices.invoices',compact('invoices'));
    }



    public function create()
    {
        $sections = Section::cursor();
        return view('invoices.add_invoice',compact('sections'));
    }



    public function store(Request $request)
    {
        $data = $request->all();
        // Invoice Table
       Invoice::create([
           'invoice_number' => $data['invoice_number'],
           'invoice_Date' => $data['invoice_Date'],
           'Due_date' => $data['Due_date'],
           'product' => $data['product'],
           'section_id' => $data['Section'],
           'Amount_collection' => $data['Amount_collection'],
           'Amount_Commission' => $data['Amount_Commission'],
           'Discount' => $data['Discount'],
           'Value_VAT' => $data['Value_VAT'],
           'Rate_VAT' => $data['Rate_VAT'],
           'Total' => $data['Total'],
           'Status' => 'غير مدفوعة',
           'Value_Status' => 2,
           'note' => $data['note'],
           'created_at' => now()
       ]);
       $invoice_id = Invoice::latest()->first()->id;
        //Insert  InvoiceDetail Table
       InvoiceDetail::create([
           'invoice_number' => $data['invoice_number'],
           'invoice_id' => $invoice_id,
           'product' => $data['product'],
           'Section' => $data['Section'],
           'Status' => 'غير مدفوعة',
           'Value_Status' => 2,
           'note' => $data['note'],
           'user' => Auth::user()->name,
           'created_at' => now()

       ]);

       if($request->hasFile('pic')){
//Insert  InvoiceAttachement Table
           $file_name = $this->uploadImage($data['pic'],'invoice_','images/Attachements/');
           $invoice_number = $data['invoice_number'];

             InvoiceAttachement::create([
                 'file_name' => $file_name,
                 'invoice_number' => $invoice_number,
                 'Created_by' => Auth::user()->name,
                 'invoice_id' => $invoice_id,
             ]);

       }
       $user = User::first();// utilisateur authentifie now
        Notification::send($user, new AddInvoice($invoice_id));

        Session::flash('success','تمت اضافة الفاتورة بنجاح');
        return redirect()->back();
    }


    public function show(Invoice $invoice)
    {
        $invoices = Invoice::where('id',$invoice->id)->first();
        $sections = Section::cursor();
          return view('invoices.show_invoice',compact('invoices','sections'));
    }

    public function Status_Update(Invoice $invoice,Request $request){
        $invoices = Invoice::findOrFail($invoice->id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            InvoiceDetail::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            InvoiceDetail::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        Session::flash('success','تم تعديل الفاتورة بنجاح');
        return redirect('/invoices');
    }


    public function getProductsSection(Section $section){
        // get product from the section id [ajax]
        $product_by_section = Product::where('section_id',$section->id)->pluck('Product_name','id');
        return json_encode($product_by_section);
    }


    public function edit(Invoice $invoice)
    {
        $invoices = Invoice::where('id',$invoice->id)->first();
        $sections = Section::cursor();
        return  view('invoices.edit_invoice',compact('invoices','sections'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->all();
        // Invoice Table
        $invoice_id =  $invoice->id;
        Invoice::where('id',$invoice_id)->update([
            'invoice_number' => $data['invoice_number'],
            'invoice_Date' => $data['invoice_Date'],
            'Due_date' => $data['Due_date'],
            'product' => $data['product'],
            'section_id' => $data['Section'],
            'Amount_collection' => $data['Amount_collection'],
            'Amount_Commission' => $data['Amount_Commission'],
            'Discount' => $data['Discount'],
            'Value_VAT' => $data['Value_VAT'],
            'Rate_VAT' => $data['Rate_VAT'],
            'Total' => $data['Total'],
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $data['note']

        ]);

        //update  InvoiceDetail Table
        InvoiceDetail::where('invoice_id',$invoice_id)->update([
            'invoice_number' => $data['invoice_number'],
            'invoice_id' => $invoice_id,
            'product' => $data['product'],
            'Section' => $data['Section'],
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $data['note'],
            'user' => Auth::user()->name,
            'created_at' => now()

        ]);


        Session::flash('success','تم تعديل الفاتورة بنجاح');
        return redirect()->back();
    }


    public function destroy(Request $request)
    {
       $id = $request->only('invoice_id');
       $invoice = Invoice::where('id',$id)->first();
       $attchements = InvoiceAttachement::where('id',$id)->first();
       $id_page = $request->id_page;
       if(!$id_page == 2){
           // la supprission phisique de donnes
              if(!empty($attchements->invoice_number)){
                  Storage::disk('public_uploads')->delete($attchements->file_name);
              }
         $invoice->forceDelete();
           Session::flash('success','تم حدف الفاتورة بنجاح');
           return redirect()->route('invoices.index');

       }else{
           // la supprission logique (Archivées)
           $invoice->Delete();
           Session::flash('success','تمت أرشفة الفاتورة بنجاح');
           return redirect()->route('invoices.index');
       }


    }

    public function  Invoice_paid(){
        $invoices = Invoice::with('section')->where('Value_Status',1)->cursor();

        return view('invoices.invoices_paid',compact('invoices'));
    }
    public function  Invoice_unpaid(){
        $invoices = Invoice::with('section')->where('Value_Status',2)->cursor();

        return view('invoices.invoices_unpaid',compact('invoices'));
    }
    public function  Invoice_partial(){
        $invoices = Invoice::with('section')->where('Value_Status',3)->cursor();

        return view('invoices.invoices_partial',compact('invoices'));
    }

    public function Print_invoice($invoice_id){

        $invoices = Invoice::where('id',$invoice_id)->first();
        return view('invoices.Print_invoice',compact('invoices'));
    }

}
