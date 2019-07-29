<?php
namespace App\Controllers\User;
use App\Models\User as User;

class Login  {

    public function index($request, $response, $args) {
        echo (User::first()->username);
        echo "HEre";
    }
}