<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the user profile page.
     */
    public function show()
    {
        $user = Auth::user();

        return view('profile.show', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $imageFile = $request->file('profile_image');

            if ($imageFile->isValid()) {
                $uploadsPath = public_path('uploads/profiles');
                if (! file_exists($uploadsPath)) {
                    mkdir($uploadsPath, 0755, true);
                }

                // Delete old profile image if exists
                if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                    unlink(public_path($user->profile_image));
                }

                // Generate unique filename
                $filename = time().'_'.$user->id.'_'.$imageFile->getClientOriginalName();

                // Move file to public uploads directory
                $imageFile->move($uploadsPath, $filename);

                // Store the public URL
                $validated['profile_image'] = '/uploads/profiles/'.$filename;
            }
        }

        // Update user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'profile_image' => $validated['profile_image'] ?? $user->profile_image,
        ]);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Check if current password is correct
        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Remove profile image.
     */
    public function removeImage()
    {
        $user = Auth::user();

        if ($user->profile_image && file_exists(public_path($user->profile_image))) {
            unlink(public_path($user->profile_image));
        }

        $user->update(['profile_image' => null]);

        return back()->with('success', 'Profile image removed successfully!');
    }
}
