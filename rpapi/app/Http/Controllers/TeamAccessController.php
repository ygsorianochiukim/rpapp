<?php

namespace App\Http\Controllers;

use App\Models\TeamAccess;
use Illuminate\Http\Request;

class TeamAccessController extends Controller
{
    public function displayTeam(){
        $teams = TeamAccess::all();

        return response()->json($teams);
    }

    public function addNewTeam(Request $request){
        $teamAccess = $request->validate([
            "s_bpartner_employee_id" => "integer|required",
            "supervisor_id" => "integer|required",
            "created_by" => "integer|required",
        ]);
        $teamAccess['is_active'] = '1';
        $teamAccess['created_date'] = date('Y-m-d H:i:s');

        $teamAccessData = TeamAccess::create($teamAccess);
        return response()->json(['Team Access Update' , $teamAccessData]);
    }
    public function listMyTeam($id){
        $teamAccess = TeamAccess::with(['user.position' , 'userAccess.position'])
        ->where('supervisor_id', $id)
        ->where('is_active', '1')
        ->get();

        return response()->json($teamAccess);
    }
    public function dropDownItem($id)
    {
        $directMembers = TeamAccess::with('user')
            ->where('supervisor_id', $id)
            ->get();

        $directMemberIds = $directMembers->pluck('s_bpartner_employee_id')->toArray();
        $secondLevelMembers = TeamAccess::with('user')
            ->whereIn('supervisor_id', $directMemberIds)
            ->get();

        $allMembers = $directMembers->merge($secondLevelMembers)
            ->unique('s_bpartner_employee_id')
            ->values();

        $formatted = $allMembers->map(function ($member) {
            return [
                's_bpartner_employee_id' => $member->user->s_bpartner_employee_id,
                'name' => $member->user->firstname . ' ' . $member->user->lastname
            ];
        });

        return response()->json($formatted);
    }
    public function removeMember($id){
        $removeMember = TeamAccess::where('team_access_id', $id)->first();
        if (!$removeMember) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found.'
            ], 404);
        }
        $removeMember->update(['is_active' => 0]);
        return response()->json([
            'success' => true,
            'message' => 'Member deactivated successfully.'
        ], 200);
    }
}
