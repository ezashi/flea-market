<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;


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
        $user->building = $validatedData['building'];

        // プロフィール画像の処理
        if ($profileRequest->hasFile('profile_image')) {
            // 既存の画像があれば削除
            if ($user->profile_image) {
                $oldImagePath = str_replace('storage/', '', $user->profile_image);
                Storage::disk('public')->delete($oldImagePath);
            }

            $filename = Str::random(20) . '.' . $profileRequest->file('profile_image')->getClientOriginalExtension();
            $profileRequest->file('profile_image')->storeAs('images/profile', $filename, 'public');
            $user->profile_image = 'storage/images/profile/' . $filename;
        }

        $user->is_profile_completed = true;
        $user->save();

        return redirect()->route('index');
    }

    public function mypageshow()
    {
        return view('mypage.profile', ['user' => Auth::user()]);
    }

    public function mypageupdata(ProfileRequest $profileRequest, AddressRequest $addressRequest)
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
        $user->building = $validatedData['building'];

        // プロフィール画像の処理
        if ($profileRequest->hasFile('profile_image')) {
            // 既存の画像があれば削除
            if ($user->profile_image) {
                $oldImagePath = str_replace('storage/', '', $user->profile_image);
                Storage::disk('public')->delete($oldImagePath);
            }

            $filename = Str::random(20) . '.' . $profileRequest->file('profile_image')->getClientOriginalExtension();
            $profileRequest->file('profile_image')->storeAs('images/profile', $filename, 'public');
            $user->profile_image = 'storage/images/profile/' . $filename;
        }

        $user->is_profile_completed = true;
        $user->save();

        return redirect()->route('mypage');
    }
}