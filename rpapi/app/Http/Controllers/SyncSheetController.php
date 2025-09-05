<?php

namespace App\Http\Controllers;

use App\Models\UserAccount;
use Illuminate\Http\Request;

class SyncSheetController extends Controller
{
    public function SyncSheetUpdate(Request $request){
        UserAccount::create([
            's_bpartner_employee_id' => $request->s_bpartner_employee_id,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'companyname' => $request->companyname,
            'sex' => $request->sex,
            'employee_no' => $request->employee_no,
            'marital_status' => $request->marital_status,
            'birthdate' => $request->birthdate,
            'image_location' => $request->image_location,
            'remaining_leave' => $request->remaining_leave,
            'created' => $request->created,
            'date_created' => $request->date_created,
            'date_updated' => $request->date_updated,
            'updated' => $request->updated,
            'is_active' => $request->is_active,
            's_bpartner_id' => $request->s_bpartner_id,
            's_bpartner_employee_group_id' => $request->s_bpartner_employee_group_id,
            's_bpartner_employee_id_approver1st' => $request->s_bpartner_employee_id_approver1st,
            's_bpartner_employee_id_approver2nd' => $request->s_bpartner_employee_id_approver2nd,
            'sss_no' => $request->sss_no,
            'hdmf_no' => $request->hdmf_no,
            'phic_no' => $request->phic_no,
            'tin_no' => $request->tin_no,
            'contact_no' => $request->contact_no,
            'address' => $request->address,
            's_bpartner_employee_id_revision' => $request->s_bpartner_employee_id_revision,
            'is_for_approval' => $request->is_for_approval,
            'email' => $request->email,
            'paf_i_company_id' => $request->paf_i_company_id,
        ]);

        return response()->json(['success' => true]);
    }
}
