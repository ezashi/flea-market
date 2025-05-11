<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProfileController extends Controller
{
    public function show()
    {
        return view('profile', ['user' => Auth::user()]);
    }

    public function update(ProfileRequest $profileRequest, AddressRequest $addressRequest)
    {
        // バリデーションに成功した値を結合
        $validatedData = array_merge(
            $profileRequest->validated(),
            $addressRequest->validated()
        );

        $user = Auth::user();

        // データの更新
        $user->name = $validatedData['name'];
        $user->postal_code = $validatedData['postal_code'];
        $user->address = $validatedData['address'];

        if (isset($validatedData['building'])) {
            $user->building = $validatedData['building'];
        }

        if ($profileRequest->hasFile('profile_image')) {
            $filename = Str::random(20) . '.' . $profileRequest->file('profile_image')->getClientOriginalExtension();
            $profileRequest->file('profile_image')->move(public_path('images/items'), $filename);
            $user->profile_image = 'images/items/' . $filename;
        }

        $user->is_profile_completed = true;
        $user->save();

        return redirect()->route('index');
    }
}