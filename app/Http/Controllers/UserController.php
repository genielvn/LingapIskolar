<?php
namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function log(Request $request)
    {
        if (
            Auth::attempt([
                "email" => $request->email,
                "password" => $request->password,
            ])
        ) {
            return redirect("/dashboard");
        }
        return back()
            ->withErrors([
                "email" => "The provided credentials do not match our records.",
            ])
            ->onlyInput("email");
    }

    public function sign(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8",
        ]);

        $newUser = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        Auth::login($newUser);

        return redirect("/dashboard");
    }
}
