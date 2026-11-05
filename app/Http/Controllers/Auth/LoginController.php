<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Response;

class LoginController extends Controller
{
    public function index(): Response
    {
        return inertia('Auth/Login');
    }
}
