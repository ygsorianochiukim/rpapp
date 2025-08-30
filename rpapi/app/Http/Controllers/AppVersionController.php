<?php

namespace App\Http\Controllers;

use App\Models\AppVersion;
use Illuminate\Http\Request;

class AppVersionController extends Controller
{
    public function checkVersion(Request $request)
    {
        $platform = $request->input('platform', 'android');
        $currentVersion = $request->input('version');

        $appVersion = AppVersion::where('platform', $platform)->first();

        if (!$appVersion) {
            return response()->json(['update_required' => false]);
        }

        $isForced = version_compare($currentVersion, $appVersion->min_version, '<');
        $isOutdated = version_compare($currentVersion, $appVersion->latest_version, '<');

        return response()->json([
            'update_required' => $isForced,
            'update_available' => $isOutdated,
            'latest_version' => $appVersion->latest_version,
        ]);
    }
}
