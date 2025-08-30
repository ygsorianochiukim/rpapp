<?php

namespace App\Http\Controllers;

use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserAccessController extends Controller
{
    public function displayUserAccess(){
        $displayAccess = UserAccess::all();

        return response()->json($displayAccess);
    }

    public function addNewAccess(Request $request){
        $validateAccess = $request->validate([
            's_bpartner_employee_id' => 'required',
            'position_id' => 'required',
            'created_by' => 'required',
        ]);
        $validateAccess['is_active'] = '1';
        $validateAccess['emp_i_user_access_id'] = Str::uuid()->toString();
        $validateAccess['date_created'] = date('Y-m-d H:i:s');
        return UserAccess::create($validateAccess);
    }
}
