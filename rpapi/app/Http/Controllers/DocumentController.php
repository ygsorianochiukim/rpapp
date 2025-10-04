<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function displayDocuments(){
        $Documents = Documents::where('is_active' , '1')->get();

        return response()->json($Documents);
    }

    public function newDocuments(Request $request){
        $documents = $request->validate([
            'doc_title' => 'string',
            'doc_link' => 'string',
            'created_by' => 'integer',
        ]);

        $documents['is_active'] = '1';
        $documents['created_date'] = date('Y-m-d H:i:s');

        $documentFields = Documents::create($documents);

        return response()->json(['Documents Added', $documentFields], 201);
    }
}
