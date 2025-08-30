<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\UserAccount;
use Dotenv\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class UserAccountController extends Controller
{
    public function display_user()
    {
        $UserAccount = UserAccount::orderBy('firstname', 'asc')->get();

        return response()->json($UserAccount);
    }

    public function add_user_account(Request $request){
        $validateUser = $request -> validate([
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'suffix' => '',
            'birthdate' => 'required|date',
            'sex' => 'required|string',
            'contact_number' => 'required|string',
            'company_name' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'is_active' => 'required',
        ]);
        $validateUser['s_bpartner_employee_id'] = Str::uuid()->toString();
        return UserAccount::create($validateUser);
    }

    public function displayUserTask($id){
        $user = UserAccount::where('s_bpartner_employee_id','=', $id)->first();

        return response()->json($user);
    }

    public function displayPersonalUser($id){
        $UserAccount = UserAccount::where('s_bpartner_employee_id','=', $id)
        ->with(['userAccess.position'])
        ->first();

        return response()->json($UserAccount);
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6'
        ]);

        $user = UserAccount::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->password = bcrypt($request->password);
        $firstNameParts = explode(' ', trim($user->firstname));
        $firstInitial = strtolower(substr($firstNameParts[0], 0, 1));
        $secondInitial = isset($firstNameParts[1]) ? strtolower(substr($firstNameParts[1], 0, 1)) : '';
        $lastNameLower = strtolower($user->lastname);
        $user->username = $firstInitial . $secondInitial . $lastNameLower;
        $user->save();
        return response()->json(['message' => 'Password and username updated successfully']);
    }
    public function sendOtp($id)
    {
        $user = UserAccount::where('s_bpartner_employee_id', '=', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $otp = rand(100000, 999999);
        $firstNameParts = explode(' ', trim($user->firstname));
        $firstInitial = strtolower(substr($firstNameParts[0], 0, 1));
        $secondInitial = isset($firstNameParts[1]) ? strtolower(substr($firstNameParts[1], 0, 1)) : '';
        $lastNameLower = strtolower($user->lastname);
        $username = $firstInitial . $secondInitial . $lastNameLower;

        $user->username = $username;
        $user->OTP = $otp;
        $user->save();
        $appId = 'a7f7ba83-2ec5-4c18-b9d6-6488429606d2';
        $tableName = 'OTP_USER_LOGIN';
        $apiKey = 'V2-MkKb8-aseH6-c4TO4-TafXv-mEzO7-BYWNb-pA0HD-0W2JU';
        $url = "https://api.appsheet.com/api/v2/apps/{$appId}/tables/{$tableName}/Action";
        $payload = [
            "Action" => "Add",
            "Properties" => [
                "Locale" => "en-US",
                "Timezone" => "Asia/Manila"
            ],
            "Rows" => [[
                "s_bpartner_employee_id" => $user->s_bpartner_employee_id,
                "contact_no"             => "0" . ltrim($user->contact_no, '0'),
                "username"               => $username,
                "otp"                    => $otp,
                "date_created"           => now()->toDateTimeString()
            ]]
        ];

        try {
            $client = new \GuzzleHttp\Client();
            $client->post($url, [
                'headers' => [
                    'ApplicationAccessKey' => $apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => $payload
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'OTP saved, but failed to send to AppSheet',
                'error'   => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'OTP generated successfully',
            'OTP' => $otp
        ]);
    }
}
