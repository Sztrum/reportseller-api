<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Aggregates;

use App\V1\Modules\Auth\Domain\Exceptions\AuthException;
use App\V1\Modules\User\Domain\Exceptions\ErrorWhileResetPasswordException;
use App\V1\Modules\User\Domain\Exceptions\InvalidEmailVerificationHashException;
use App\V1\Modules\User\Domain\Exceptions\UserEmailAlreadyVerifiedException;
use App\V1\Modules\User\Domain\Exceptions\UserEmailIsNotVerifiedException;
use App\V1\Modules\User\Domain\Exceptions\UserNotFoundException;
use App\V1\Modules\User\Domain\Exceptions\UserWithEmailExistsException;
use App\V1\Modules\User\Domain\Exceptions\UserWithEmailNotExistsException;
use App\V1\Modules\User\Domain\Models\User;
use App\V1\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\Hashing\Hasher;
use Throwable;

readonly class UserAggregate
{
    public function __construct(
        private UserRepository $repository,
        private Hasher $hasher
    ) {
    }

    /**
     * @throws Throwable
     */
    public function checkUserWithEmailAlreadyExist(
        string $email
    ): void {
        $this->ensureUserNotExists($this->repository->firstByEmail($email));
    }

    /**
     * @throws InvalidEmailVerificationHashException|Throwable
     */
    public function verifyEmailVerificationHash(
        User $user,
        string $hash,
        string $exception = InvalidEmailVerificationHashException::class
    ): void {
        throw_if(!$user->verifyEmailVerificationHash($hash), $exception);
    }

    /**
     * @throws UserEmailAlreadyVerifiedException|Throwable
     */
    public function userEmailAlreadyVerified(
        User $user,
        string $exception = UserEmailAlreadyVerifiedException::class
    ): void {
        throw_if($user->hasVerifiedEmail(), $exception);
    }

    /**
     * @throws UserEmailIsNotVerifiedException|Throwable
     */
    public function validateVerifiedEmail(
        User $user,
        string $exception = UserEmailIsNotVerifiedException::class
    ): void {
        throw_if(!$user->hasVerifiedEmail(), $exception);
    }

    /**
     * @throws Throwable
     */
    public function verifyPassword(
        User $user,
        string $password,
        string $exception = AuthException::class,
    ): void {
        throw_if(!$this->hasher->check($password, $user->password), $exception);
    }

    /**
     * @throws UserWithEmailNotExistsException|Throwable
     */
    public function checkUserWithEmailNotExist(
        string $email,
        string $exception = UserWithEmailNotExistsException::class
    ): void {
        $this->ensureUserExists($this->repository->firstByEmail($email), $exception);
    }

    /**
     * @throws ErrorWhileResetPasswordException|Throwable
     */
    public function verifyResetPasswordStatus(
        string $status,
        string $exception = ErrorWhileResetPasswordException::class,
    ): void {
        throw_if($status !== PasswordBrokerContract::PASSWORD_RESET, $exception);
    }

    /**
     * @throws UserNotFoundException|Throwable
     */
    public function ensureUserExists(
        ?User $user,
        string $exception = UserNotFoundException::class,
    ): void {
        throw_if($user === null, $exception);
    }

    /**
     * @throws UserWithEmailExistsException|Throwable
     */
    public function ensureUserNotExists(
        ?User $user,
        string $exception = UserWithEmailExistsException::class,
    ): void {
        throw_if($user !== null, $exception);
    }
}
