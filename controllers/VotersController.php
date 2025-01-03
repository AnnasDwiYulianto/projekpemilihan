<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VotersController extends Controller
{
    
    public function index(Request $request)
    {
        $voters = User::where('role', 'voter')->get()->map(function ($user) {
            $status = $user->choice !== null ? 'Voted' : 'Not Voted';
            $user->status = $status;
            return $user;
        });

        return view('voters.index', [
            'title' => 'E-Voting-HMPS | Voters List',
            'voters' => $voters
        ]);
    }

   
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8',
        ], [
            'email.unique' => 'Email is taken.',
        ]);

        $validatedData['password'] = Hash::make($request->password);
        $validatedData['role'] = 'voter';

        User::create($validatedData);

        return redirect()->route('voters.index')->with('message', 'Data added succesfully!');
    }


    public function show(User $user)
    {
        //
    }

    
    public function edit(string $id)
    {
        $voters = User::findOrFail($id);
        return response()->json($voters);
    }

    
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
        ]);
        $voters = User::findOrFail($id);
        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        }

        $voters->update($validatedData);

        return redirect()->route('voters.index')->with('message', 'Data updated successfully!');
    }

    
    public function destroy(string $id)
    {
        User::destroy($id);
        return redirect()->route('voters.index')->with('message', 'Data deleted successfully!');
    }
}
