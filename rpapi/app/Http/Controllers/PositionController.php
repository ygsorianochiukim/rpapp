<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use Illuminate\Support\Str;

class PositionController extends Controller
{
    public function displayPosition(){
        $displayPosition = Position::all();
        
        return response()->json($displayPosition);
    }

    public function newPosition(Request $request){
        $validatedPostion = $request->validate([
            'position' => 'string|required',
            'department' => 'string|required',
            'function' => 'string|required',
            'created_by' => 'required',
        ]);
        $validatedPostion['is_active'] = '1';
        $validatedPostion['date_created'] = date('Y-m-d H:i:s');

        return Position::create($validatedPostion);
    }
}
