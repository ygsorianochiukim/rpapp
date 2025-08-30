<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AccessLine;

class AccessLineController extends Controller
{
    public function displayAccessLine(){
        $displayAccessLine = AccessLine::all();

        return response()->json($displayAccessLine);
    }

    public function addAccessLine(Request $request){
        $validatedAccessLine = $request->validate([
            's_bpartner_employee_id' => 'required',
            'access_type' => 'string|required',
            'created_by' => 'required',
        ]);
        $validatedAccessLine['is_active'] = '1';
        $validatedAccessLine['date_created'] = date('Y-m-d H:i:s');

        return AccessLine::create($validatedAccessLine);
    }
}
