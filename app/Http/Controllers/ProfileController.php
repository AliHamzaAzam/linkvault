<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display a public user profile.
     */
    public function show(string $username): View
    {
        $user = User::where('username', $username)->firstOrFail();

        // Get public bookmarks with their tags and collections
        $bookmarks = $user->bookmarks()
            ->where('is_public', true)
            ->with(['tags', 'collections'])
            ->latest()
            ->paginate(20);

        // Get public collections with count of public bookmarks
        $collections = $user->collections()
            ->where('is_public', true)
            ->withCount(['bookmarks' => function ($query) {
                $query->where('is_public', true);
            }])
            ->orderBy('position')
            ->get();

        return view('profile.show', compact('user', 'bookmarks', 'collections'));
    }

    /**
     * Display a public collection.
     */
    public function collection(string $username, string $slug): View
    {
        $user = User::where('username', $username)->firstOrFail();

        $collection = Collection::where('user_id', $user->id)
            ->where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        // Get public bookmarks in this collection
        $bookmarks = $collection->bookmarks()
            ->where('is_public', true)
            ->with(['tags', 'collections'])
            ->paginate(20);

        // Get all public collections for navigation
        $collections = $user->collections()
            ->where('is_public', true)
            ->withCount(['bookmarks' => function ($query) {
                $query->where('is_public', true);
            }])
            ->orderBy('position')
            ->get();

        return view('profile.collection', compact('user', 'collection', 'bookmarks', 'collections'));
    }
}
