<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class ControlPanelController extends Controller
{
    public function index () {
        $users = User::all();
        return view('controlPanel', compact('users'));
    }
}
