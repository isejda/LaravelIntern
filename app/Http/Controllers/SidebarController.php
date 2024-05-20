<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SidebarController extends Controller
{
    public function getUser()
    {
        return Auth::user();
    }
    public function getImageURL()
    {
        $user = Auth::user();

        if ($user->image) {
            return asset('storage/' . $user->image);
        } else {
            return asset('storage/profilePhoto/4vZgagusKPGkhjLKViwyzTJrnLA1NgzTUCqXfZxT.jpg');
        }
    }

}
