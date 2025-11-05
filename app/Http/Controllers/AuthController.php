<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\KepalaDapur;
use App\Models\Distributor;

class AuthController extends Controller
{
    // ========== OWNER ==========
    public function prosesloginowner(Request $request)
    {
        if (Auth::guard('owner')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            return redirect()->intended('/owner/dashboardowner');
        }

        return redirect('/owner')->with(['warning' => 'Email atau Password Salah!']);
    }

    // ========== ADMIN ==========
    public function prosesloginadmin(Request $request)
    {
        $admin = Admin::where('email_admin', $request->email)
            ->where('password_admin', $request->password) // tanpa hash
            ->first();

        if ($admin) {
            Auth::guard('admin')->login($admin);
            return redirect()->intended('/admin/dashboardadmin');
        }

        return redirect('/admin')->with(['warning' => 'Email atau Password Salah!']);
    }

    // ========== KEPALA DAPUR ==========
    public function prosesloginkepaladapur(Request $request)
    {
        $kepala_dapur = KepalaDapur::where('email', $request->email)
            ->where('password', $request->password)
            ->first();

        if ($kepala_dapur) {
            Auth::guard('kepala_dapur')->login($kepala_dapur);
            return redirect()->intended('/kepala_dapur/dashboardkepaladapur');
        }

        return redirect('/kepala_dapur')->with(['warning' => 'Email atau Password Salah!']);
    }

    // ========== DISTRIBUTOR ==========
    public function proseslogindistributor(Request $request)
    {
        $distributor = Distributor::where('email_distributor', $request->email_distributor)
            ->where('password_distributor', $request->password_distributor)
            ->first();

        if ($distributor) {
            Auth::guard('distributor')->login($distributor);
            return redirect()->intended('/distributor/dashboarddistributor');
        }

        return redirect('/distributor')->with(['warning' => 'Email atau Password Salah!']);
    }

    // ========== LOGOUT ==========
    public function proseslogoutowner()
    {
        Auth::guard('owner')->logout();
        return redirect('/owner');
    }

    public function proseslogoutadmin()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin');
    }

    public function proseslogoutkepaladapur()
    {
        Auth::guard('kepala_dapur')->logout();
        return redirect('/kepala_dapur');
    }

    public function proseslogoutdistributor()
    {
        Auth::guard('distributor')->logout();
        return redirect('/distributor');
    }
}