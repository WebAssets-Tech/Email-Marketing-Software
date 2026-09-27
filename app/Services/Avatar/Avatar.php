<?php

namespace App\Services\Avatar;

class Avatar {
    public static function create(string $name) {
        return "https://api.dicebear.com/7.x/initials/svg?seed=$name&scale=80&radius=50&backgroundColor=c0ca33,e53935,f4511e,fb8c00,fdd835,ffb300,d1d4f9,d81b60,00897b,00acc1,039be5,1e88e5,3949ab,5e35b1,43a047,8e24aa,7cb342&backgroundRotation=360,30";
    }
}
