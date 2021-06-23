<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FileController extends Controller
{
    protected $file_validations;
    protected $file_key_name;

    public function __construct()
    {
        $this->file_validations = [
            'disk' => Rule::in(['local', 'public']),
        ];
        $this->file_key_name = 'file';
    }

    // public function testDownload($path)
    // {
    //     $path = public_path('storage/images/') . $path;       
    //     //dd (Storage::disk('public')->files('images')); 
    //     //dd(Storage::disk('public')->exists('images/'.$path));
    //     //return Storage::download($path);
    //     //return Storage::disk('public')->get('images/'.$path);
    //     return response()->download($path);
    // }

    public function uploadImage(Request $request)
    {
        return $this->upload($request, [$this->file_key_name => 'image|max:5000'], 'images');
    }

    public function uploadDocument(Request $request)
    {
        $ms_office_mimes = 'doc,dot,docx,dotx,docm,dotm,xls,xlt,xla,xlsx,xltx,xlsm,xltm,xlam,xlsb,ppt,pot,pps,ppa,pptx,potx,ppsx,ppam,pptm,potm,ppsm,mdb';
        $open_office = 'odt,ods,odp';
        $other_docs = 'pdf,txt,rtf,tex,wpd';
        $mimes = "$ms_office_mimes,$open_office,$other_docs";
        return $this->upload($request, [$this->file_key_name => "file|mimes:$mimes|max:10000"], 'documents');
    }

    private function upload($request, $additional_rules, $folder)
    {
        $validation = array_merge($this->file_validations, $additional_rules);
        $data = $request->validate($validation);
        $path = $request->file('file')->store($folder, $data['disk'] ?? 'local');
        return response()->json([
            'path' => $path,
        ], 201);
    }
}
