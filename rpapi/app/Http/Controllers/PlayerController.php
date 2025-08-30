<?php

namespace App\Http\Controllers;

use App\Models\UserAccount;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function save(Request $request)
    {
        $user = UserAccount::where('s_bpartner_employee_id' ,'=', '3723')->first();

        if ($user) {
            $user->player_id = $request->player_id;
            $user->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'User not found'], 404);
    }
}
