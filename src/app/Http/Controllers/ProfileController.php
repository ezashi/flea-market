<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
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
        $user->profile_image = $validatedData['profile_image'] ?? $user->profile_image;
        $user->name = $validatedData['name'];
        $user->postal_code = $validatedData['postal_code'];
        $user->address = $validatedData['address'];
        $user->building = $validatedData['building'];

        if ($profileRequest->hasFile('profile_image')) {
            $filename = Str::random(20) . '.' . $profileRequest->file('profile_image')->getClientOriginalExtension();
            $profileRequest->file('profile_image')->store('images/items', 'public');
            $user->profile_image = 'storage/' . $filename;

            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                unlink(public_path($user->profile_image));
            }
        }

        $user->is_profile_completed = true;
        $user->save();

        return redirect()->route('index');
    }
}