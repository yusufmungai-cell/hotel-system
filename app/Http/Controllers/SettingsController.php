<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    // SETTINGS PAGE
    public function index()
    {
        $settings = Setting::pluck('value','key')->toArray();

        return view('settings.system', compact('settings'));
    }

    // SAVE SETTINGS
    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success','Settings saved successfully');
    }
}