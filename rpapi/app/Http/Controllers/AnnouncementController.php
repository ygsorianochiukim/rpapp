<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function displayAnnouncements(){
        $announcements = Announcement::all();

        return response()->json($announcements);
    }

    public function addNewAnnouncement(Request $request){
        $announceFields = $request->validate([
            'announcement_description' => 'string|required',
            'date_validity' => 'date|required',
            'created_by' => 'integer|required',
        ]);

        $announceFields['is_active'] = '1';
        $announceFields['created_date'] = date('Y-m-d H:i:s');

        $announcemInformation = Announcement::create($announceFields);
        return response()->json($announcemInformation);
    }
}
