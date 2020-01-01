<?php

namespace App\Services;

use App\User;
use App\Profile;
use Laravel\Socialite\Contracts\User as ProviderUser;

/**
 * ProfileService class.
 * 
 * @package App\Services
 * @author Nascent Africa <nascent.afrique@gmail.com>
 */
class ProfileService
{
    /**
     * Create or get user
     *
     * @param ProviderUser $providerUser
     * @return mixed
     */
    public function createOrGetUser(ProviderUser $providerUser)
    {
        $account = Profile::whereProvider('facebook')
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {

            return $account->user;

        } else {

            $account = new Profile([
                'provider' => 'facebook',
                'provider_user_id' => $providerUser->getId(),
                'provider_user_avatar' => $providerUser->getAvatar()
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                    'password' => md5(rand(1,10000)),
                ]);
            }

            $account->user()->associate($user);
            
            $account->save();

            return $user->load('profile');
        }
    }
}