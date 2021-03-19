<?php

namespace App\Http\Controllers;

use App\InvoiceAttachement;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Session;
use Auth;
class InvoiceAttachementController extends Controller
{
    use ImageUpload;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [

            'file_name' => 'mimes:pdf,jpeg,png,jpg',

        ], [
            'file_name.mimes' => 'صيغة المرفق يجب ان تكون   pdf, jpeg , png , jpg',
        ]);
        $file_name = $this->uploadImage($request->file_name,'invoice_','Images/Attachements/');
        $attachments =  new InvoiceAttachement();
        $attachments->file_name = $file_name;
        $attachments->invoice_number = $request->invoice_number;
        $attachments->invoice_id = $request->invoice_id;
        $attachments->Created_by = Auth::user()->name;
        $attachments->save();
        Session::flash('success', 'تم اضافة المرفق بنجاح');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InvoiceAttachement  $invoiceAttachement
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceAttachement $invoiceAttachement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InvoiceAttachement  $invoiceAttachement
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceAttachement $invoiceAttachement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InvoiceAttachement  $invoiceAttachement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceAttachement $invoiceAttachement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InvoiceAttachement  $invoiceAttachement
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceAttachement $invoiceAttachement)
    {
        //
    }
}
