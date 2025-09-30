<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SyncSheetController extends Controller
{
    public function SyncSheetUpdate(Request $request)
    {
        UserAccount::updateOrCreate(
            ['s_bpartner_employee_id' => $request->s_bpartner_employee_id],
            [
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'companyname' => $request->companyname,
                'sex' => $request->sex,
                'employee_no' => $request->employee_no,
                'marital_status' => $request->marital_status,
                'birthdate' => $this->formatDate($request->birthdate),
                'image_location' => $request->image_location,
                'remaining_leave' => $request->remaining_leave,
                'created' => $this->formatDate($request->created),
                'date_created' => $this->formatDate($request->date_created),
                'date_updated' => $this->formatDate($request->date_updated),
                'updated' => $this->formatDate($request->updated),
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
            ]
        );
        return response()->json(['success' => true]);
    }
    private function formatDate($value)
    {
        if (!$value) return null;
        try {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function SyncSheetPosition(Request $request)
    {
        $function = 'Maker';
        $positionName = strtolower($request->position_name);

        if (str_contains($positionName, 'checker')) {
            $function = 'Checker';
        } elseif (str_contains($positionName, 'planner')) {
            $function = 'Planner';
        } elseif (str_contains($positionName, 'maker')) {
            $function = 'Maker';
        }

        $positionSetup = Position::create([
            'position_id'   => $request->position_id,
            'position'      => $request->position_name,
            'function'      => $function,
            'is_active'     => 1,
            'created_by'    => 2832,
            'date_created'  => now(),
        ]);

        return response()->json(['success' => true, 'data' => $positionSetup]);
    }
}
