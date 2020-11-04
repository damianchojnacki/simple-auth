<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmEmail;
use App\Mail\AccountDeleted;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Shows registration page.
     */
    public function registration()
    {
        return view('register');
    }

    /**
     * Shows email confirmed page.
     */
    public function confirmed()
    {
        if(!Auth::check()){
            return redirect()->route('auth.registration');
        }

        return view('confirm');
    }

    /**
     * Registers user.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if($validation->fails()){
            return response()->json([
                'message' => $validation->errors(),
            ], 422);
        }

        //creates user
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'hash' => Str::random(32),
        ]);

        //sends email confirmation
        Mail::to($request->email)->send(new ConfirmEmail($user));

        return response()->json([
            'message' => "Please confirm your email.",
        ]);
    }

    /**
     * Confirms user email.
     * Checks if url match user id, hash and if user isn't verified yet.
     *
     * @param $id - User id
     * @param $hash - User hash
     *
     * @return RedirectResponse
     */
    public function confirmEmail($id, $hash)
    {
        $user = User::where('id', $id)->where('hash', $hash)->whereNull('email_verified_at')->first();

        if(!$user){
            return redirect()->route('auth.email.confirmed')->withErrors(['link' => 'Bad link. Please try again.']);
        }

        $user->markEmailAsVerified();

        Auth::login($user);

        return redirect()->route('auth.email.confirmed');
    }

    /**
     * Deletes user account.
     * Checks if axios call match user id, hash. And If so, deletes account.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $user = User::where('id', $request->id)->where('hash', $request->hash)->first();

        if(!$user){
            return response()->json([
                'message' => "Credentials mismatch.",
            ], 422);
        }

        $user->delete();

        //sends email notification
        Mail::to($user->email)->send(new AccountDeleted($user));

        return response()->json([
            'message' => "Account deleted.",
        ]);
    }
}
