<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserAlias;
use App\User;
use PragmaRX\Google2FA\Google2FA;
use PragmaRX\Google2FAQRCode\QRCode;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\QrCode as EndroidQrCode; 
use Endroid\QrCode\Writer\PngWriter;


class Login2FAController extends Controller
{
    //已經有綁定2FA的顯示
    public function show2faForm(Request $request)
    {
        $subuser = UserAlias::find($request->input('sub_user_id'));

        if (!$subuser && !session()->has('2fa:user:id')) {
            return redirect()->route('auth.login');
        }

        return view('admin.auth.2fa', compact('subuser'));
    }
    //驗證2FA
    public function verify2fa(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6',
        ]);

        $user = null;

        if (session('2fa:user:id')) {
            $user = User::find(session('2fa:user:id'));
        }

        if (!$user && $request->filled('sub_user_id')) {
            $user = UserAlias::find($request->input('sub_user_id'));

        }

        $google2fa = new Google2FA();

        $secret = ($user instanceof UserAlias)
            ? $user->aliases_google_2fa_secret
            : $user->google2fa_secret;

        $isValid = $google2fa->verifyKey($secret, $request->input('one_time_password'));

        if (!$isValid) {
            return redirect()->back()->with('error', '驗證碼錯誤');
        }

        if ($user instanceof UserAlias) {
            auth()->login($user->user);
            session()->forget('2fa:user:id');

            return redirect('/admin');
        } else {
            // 驗證成功 → 登入並清除 session
            Auth::login($user);
            session()->forget('2fa:user:id');

            return redirect('/admin');
        }

    }
    //驗證初次設定的2FA
    public function store2faSecret(Request $request)
    {

        $request->validate([
            'one_time_password' => 'required|digits:6',
        ]);
        
        $user = null;

        if (session('2fa:temp_user_id')) {
            $user = User::find(session('2fa:temp_user_id'));
        }

        if (!$user && $request->filled('sub_user_id')) {
            $user = UserAlias::find($request->input('sub_user_id'));

        }

        if (!$user) {
            return redirect()->route('auth.login')->with('error', '使用者不存在');
        }

        $google2fa = new Google2FA();

        $secret = session('2fa:secret');

        if (!$secret || strlen($secret) < 16) {
            return back()->with('error', '密鑰無效，請重新設置');
        }

        $valid = $google2fa->verifyKey($secret, $request->input('one_time_password'), 2);


        if (!$valid) {
            return redirect()->back()->with('error', '驗證碼無效，請重新掃描 QR Code');
        }

        if ($user instanceof UserAlias) {
            $user->aliases_google_2fa_secret = $secret;
        } else {
            $user->google2fa_secret = $secret;
        }
    
        $user->save();
    
        // 清掉 session
        Auth::logout();
        session()->forget([
            '2fa:temp_user_id',
            '2fa:sub_temp_user_id',
            '2fa:secret',
            '2fa:user:id',
            '2fa:sub_user:id',
        ]);

        return redirect('/admin/login')->with('success', 'Google 驗證器設定完成，請重新登入');
    }
    //第一次用2FA的顯示
    public function show2faSetupForm(Request $request)
    {

        $user = User::find(session('2fa:temp_user_id'));

        $subuser = UserAlias::find($request->input('sub_user_id'));


        if (!$user && !$subuser) {
            return redirect()->back()->with('error', '使用者不存在');
        }
        if($user && !$subuser){

            $google2fa = new Google2FA();

            $secret = $google2fa->generateSecretKey();

            session(['2fa:secret' => $secret]);

            $otpUrl = "otpauth://totp/內政部消防署:{$user->username}?secret={$secret}&issuer=內政部消防署";

            $qrCode = new EndroidQrCode($otpUrl);


            $writer = new PngWriter();
            $imageData = $writer->write($qrCode)->getString();
        
            $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($imageData);

            return view('admin.auth.2fa_setup', [
                'qrCodeUrl' => $qrCodeBase64,
                'secret' => $secret
            ]);
        }
        if(!$user && $subuser){

            $google2fa = new Google2FA();

            $secret = $google2fa->generateSecretKey();

            session(['2fa:secret' => $secret]);

            $otpUrl = "otpauth://totp/內政部消防署:{$subuser->username}?secret={$secret}&issuer=內政部消防署";

            $qrCode = new EndroidQrCode($otpUrl);


            $writer = new PngWriter();
            $imageData = $writer->write($qrCode)->getString();
        
            $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($imageData);

            return view('admin.auth.2fa_setup', [
                'qrCodeUrl' => $qrCodeBase64,
                'secret' => $secret,
                'subuser' => $subuser,
            ]);
        }
    }
}
