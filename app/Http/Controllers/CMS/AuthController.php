<?php


namespace App\Http\Controllers\CMS;


use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Traits\CustomRequest;
use App\Services\User\AuthService;

class AuthController extends Controller
{
    use CustomRequest;
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function signIn(AuthRequest $request)
    {
        $data = $this->data($request);
        $user = $this->authService->signInWithEmail($data);

        if (!$user) {
            $this->error(messages('auth.wrong_credentials'));
        }
    }
}
