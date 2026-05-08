<?php
namespace App\Services;

use App\Repositories\Contracts\AuthRepositoryInterface;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(array $data)
    {
        return $this->authRepository->register($data);
    }

    public function login(array $data)
    {
        return $this->authRepository->login($data);
    }
}
