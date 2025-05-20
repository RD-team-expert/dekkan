<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('users.create');
    }

    public function store(UserRequest $request): \Illuminate\Http\RedirectResponse
    {
        User::create($request->validated());
        return redirect()->route('users.index')->with('success', 'Created successfully');
    }

    public function show(User $user): \Illuminate\Contracts\View\View
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user): \Illuminate\Contracts\View\View
    {
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $user->update($request->validated());
        return redirect()->route('users.index')->with('success', 'Updated successfully');
    }

    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Deleted successfully');
    }
}
