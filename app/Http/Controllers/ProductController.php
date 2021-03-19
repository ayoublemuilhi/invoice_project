<?php

namespace App\Http\Controllers;

use App\Http\Requests\products\ProductRequest;
use App\Product;
use App\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index()
    {
        $products  = Product::with('section')->cursor();
        $sections = Section::cursor();

        return view('products.products',compact('products','sections'));
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
    public function store(ProductRequest $request)
    {
        $data = $request->all();
        Product::create([
            'Product_name' => $data['Product_name'],
            'description' => $data['description'],
            'section_id' => $data['section_id'],
            'created_at' => now()
        ]);
        Session::flash('success','تمت اضافة المنتج بنجاح');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }





    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request)
    {

        $section_id = Section::where('section_name',$request->section_id)->first()->id;

         $product = Product::find($request->pro_id);

        $product->update([
            'Product_name' =>$request->Product_name,
            'description' => $request->description,
            'section_id' => $section_id,

        ]);
        Session::flash('success','تمت تعديل المنتج بنجاح');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request)
    {

        $product = Product::find($request->pro_id);
        $product->delete();

        Session::flash('success','تم حدف المنتج بنجاح');
        return redirect()->back();
    }
}
