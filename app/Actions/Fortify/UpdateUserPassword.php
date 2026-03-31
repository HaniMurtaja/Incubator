<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    public function update($user, array $input)
    {
        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        if (! Hash::check($input['current_password'], $user->password)) {
            Validator::make([], [])->after(function ($validator) {
                $validator->errors()->add('current_password', __('The current password is incorrect.'));
            })->validate();
        }

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}

