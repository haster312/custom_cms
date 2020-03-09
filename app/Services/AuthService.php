<?php

namespace App\Services\User;

use App\Helpers\StringHelper;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Traits\CustomResponse;

class AuthService
{
    use CustomResponse;
    public $userRepo;
    public $roles;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
        $this->roles = config('roles');
    }

    /**
     * Sign in with email & password
     * @param $input
     * @return mixed
     */
    public function signInWithEmail($input)
    {
        $hash = config('app.hash');

        $checkUser = $this->userRepo->model
            ->where('email', $input['email'])
            ->orWhere('phone_number', $input['email'])
            ->first();


        if (!$checkUser) {
            return false;
        }

        $credentials = ['email' => $checkUser->email, 'password' => $input['password'] . $hash];
        $user = Auth::attempt($credentials);


        if (!$user) {
            return false;
        }

        return $this->getToken();
    }

    /**
     * Sign in with user model
     * @param $user
     * @return bool|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function signInWithUser($user)
    {
        Auth::login($user);
        return $this->getToken();
    }

    /**
     * Get access token from authentication
     *
     * @return bool|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getToken()
    {
        $user = Auth::user();

        $token = $user->createToken(config('app.name'))->accessToken;

        if ($token) {
            $loggedUser = Auth::user();
            $loggedUser->token = $token;

            Auth::setUser($loggedUser);
            unset($loggedUser->tokens);

            return $loggedUser;
        }

        return false;
    }
}
