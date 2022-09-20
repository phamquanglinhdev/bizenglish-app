<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function autoLogin($private = null)
    {
        if ($private == null) {
            return view("errors.500");
        }
        $user = User::where("private_key", "=", $private)->first();
        if (isset($user->id)) {
            backpack_auth()->loginUsingId($user->id, true);
        }
        return redirect("/admin/dashboard");
    }
}
