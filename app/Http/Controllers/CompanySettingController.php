<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanySetting;


class CompanySettingController extends Controller
{
   public function index()
{
    $settings = CompanySetting::first();

    if (!$settings) {
        $settings = CompanySetting::create([
            'company_name' => 'My Hotel',
            'phone' => '',
            'email' => '',
            'address' => '',
            'currency' => 'KES',
            'logo' => null
        ]);
    }

    return view('settings.index', compact('settings'));
}
public function update(Request $request)
{
    $settings = CompanySetting::first();

    $data = $request->only([
        'company_name',
        'phone',
        'email',
        'address',
        'currency'
    ]);

    if ($request->hasFile('logo')) {
        $data['logo'] = $request->file('logo')->store('logos', 'public');
    }

    $settings->update($data);

    return back()->with('success', 'Settings saved successfully');
}
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'email' => 'nullable|email',
            'logo' => 'nullable|image|max:2048'
        ]);

        $settings = CompanySetting::first();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $settings->logo = $path;
        }

        $settings->company_name = $request->company_name;
        $settings->address = $request->address;
        $settings->phone = $request->phone;
        $settings->email = $request->email;
        $settings->currency = $request->currency;

        $settings->save();

        return back()->with('success', 'Settings updated successfully');
    }
}
