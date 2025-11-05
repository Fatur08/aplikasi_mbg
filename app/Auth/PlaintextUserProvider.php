<?php
namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class PlaintextUserProvider extends EloquentUserProvider
{
    public function validateCredentials(UserContract $user, array $credentials)
    {
        // cari nilai password di $credentials dengan prioritas key 'password'
        $plain = null;
        //dd($credentials);


        if (array_key_exists('password', $credentials)) {
            $plain = $credentials['password'];
        } else {
            // cari key mana pun yang mengandung kata 'password' (case-insensitive)
            foreach ($credentials as $key => $value) {
                if (stripos($key, 'password') !== false) {
                    $plain = $value;
                    break;
                }
            }
        }

        // kalau tidak ditemukan, kembalikan false (gagal)
        if ($plain === null) {
            return false;
        }

        // ambil password dari model (model harus override getAuthPassword bila kolom beda nama)
        return $plain === $user->getAuthPassword();
    }
}