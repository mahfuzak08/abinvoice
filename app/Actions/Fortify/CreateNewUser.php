<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();
        try{
            return User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'mobile' => empty($input['mobile']) ? null : $input['mobile'],
                'address' => empty($input['address']) ? null : $input['address'],
                'role_id' => empty($input['role']) ? 4 : $input['role'],
                'password' => Hash::make($input['password']),
            ]);
        }catch(\Exception $e){
            dd($e);
        }
    }
}
