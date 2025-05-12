<?php

namespace App\Actions\Fortify;

use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{

 use PasswordValidationRules;
    /**
     * Validate and create a newly registered pelanggan.
     *
     * @param  array<string, string>  $input
     * @return \App\Models\Pelanggan
     */
    public function create(array $input): Pelanggan
    {
        Validator::make($input, [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username'     => ['required', 'string', 'max:255', 'unique:pelanggans,username'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:pelanggans,email'],
            'password'     => $this->passwordRules(),
            'no_telepon'   => ['nullable', 'string', 'max:20'],
            'alamat'       => ['nullable', 'string', 'max:500'],
            'terms'        => Jetstream::hasTermsAndPrivacyPolicyFeature()
                              ? ['accepted', 'required']
                              : [],
        ])->validate();

        return Pelanggan::create([
            'nama_lengkap' => $input['nama_lengkap'],
            'username'     => $input['username'],
            'email'        => $input['email'],
            'password'     => Hash::make($input['password']),
            'no_telepon'   => $input['no_telepon'] ?? null,
            'alamat'       => $input['alamat'] ?? null,
        ]);
    }
}
