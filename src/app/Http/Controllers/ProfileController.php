<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserDetailUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'user' => $request->user()->load(['userDetail', 'userImage']),
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $userImage = $request->user()->userImage;
        $file_name = $userImage->image_path;

        if ($request->file('file')) {
            // 古いファイルが存在するか確認し、存在する場合は削除
            if (Storage::exists('public/images/users/' . $file_name)) {
                Storage::delete('public/images/users/' . $file_name);
            }
            // 新しいファイル名を生成し、ファイルを保存
            $file_name = date('Ymd') . Str::random(15) . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs('public/images/users/', $file_name);
            // データベースに保存
            $userImage->image_path = $file_name;
            $userImage->save();
        }

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Update the user's profile information.
     */
    public function updateUserDetail(UserDetailUpdateRequest $request): RedirectResponse
    {
        $userDetail = $request->user()->userDetail;
        $userDetail->update($request->only(['postal', 'address', 'building', 'introduction']));

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
