<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// class VerifyEmailController extends Controller
// {
//     public function sendMail(Request $request)
//     {
//         \Mail::to(auth()->user())->send(new EmailVerification(auth()->user()));

//         return response()->json(['message' => 'Email verification sent successfully.']);
//     }

//     public function verifyEmail(Request $request)
//     {
//         if (!$request->user()->email_verified_at) {
//             $request->user()->forceFill([
//                 'email_verified_at' => now(),
//             ])->save();
//         }
//         return response()->json(['message' => 'Email verified successfully.']);
//     }
// }
