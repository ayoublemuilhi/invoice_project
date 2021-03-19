<?php

namespace App\Http\Controllers;

use App\Http\Requests\sections\SectionRequest;
use App\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class SectionController extends Controller
{

    public function index()
    {
        $sections  = Section::cursor();

        return view('sections.sections',compact('sections'));

    }


    public function create()
    {
        //
    }

    public function store(SectionRequest $request)
    {
        $data = $request->all();
        Section::create([
            'section_name' => $data['section_name'],
            'description' => $data['description'],
            'Created_by' => Auth::user()->name,
            'created_at' => now()
        ]);
        Session::flash('success','تمت اضافة القسم بنجاح');
        return redirect()->back();
    }


    public function show(Section $section)
    {
        //
    }

    public function edit(Section $section)
    {
        //
    }

    public function update(Request $request)
    {
        $id = $request->id;
        // validation
        $request->validate([
            'section_name' => 'bail|required|max:255|unique:sections,section_name,'.$id,

        ],[
                'section_name.required' => 'اسم القسم مطلوب',
                'section_name.unique' => ' القسم مسجل مسبقا',
                'section_name.max' => 'لا يجوز أن يكون اسم القسم أكبر من 255 حرفًا'
        ]);

        // modification
        $section = Section::find($id);
       $section->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
           ]);
        Session::flash('success','تم تغديل القسم بنجاح');
        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $section = Section::find($request->id);
        $section->delete();

        Session::flash('success','تم حدف القسم بنجاح');
        return redirect()->back();
    }
}
