<?php

namespace App\Http\Controllers;

use App\Models\CashBorrow;
use Illuminate\Http\Request;

class CashBorrowController extends Controller
{
    public function displayCashBorrow(){
        $displayList = CashBorrow::all();
        return response()->json($displayList);
    }

    public function storeCashBorrow(Request $request){
        
    }
}
