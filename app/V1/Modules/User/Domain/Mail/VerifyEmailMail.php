<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Mail;

use App\V1\Core\Domain\Domain\Services\FrontendEndpointService;
use App\V1\Core\Domain\Enums\FrontEndRouteEnum;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly User $user,
    ) {
    }

    /**
     * @throws \Throwable
     */
    public function build(FrontendEndpointService $frontendEndpointService): VerifyEmailMail
    {
        $userId = $this->user->getKey();
        if (!is_string($userId) && !is_int($userId)) {
            $userId = $this->user->id;
        }

        return $this->subject(
            __('user::emails.verify-email.subject')
        )
            ->view('user::emails.verify-email')
            ->with([
                'user' => $this->user,
                'frontendUrl' => $frontendEndpointService->route(FrontEndRouteEnum::AUTH_VERIFY_EMAIL, [
                    'user_id' => $userId,
                    'hash' => $this->user->generateHashToEmailVerification(),
                ]),
            ]);
    }
}
