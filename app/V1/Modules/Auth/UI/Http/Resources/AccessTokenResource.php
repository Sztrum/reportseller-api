<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\NewAccessToken;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property NewAccessToken $resource
 */
class AccessTokenResource extends JsonResource
{
    /** @var string|null */
    public static $wrap = null;

    public function __construct(NewAccessToken $resource)
    {
        parent::__construct($resource);
    }

    /**
     * @return array<string, int|string>
     */
    public function toArray($request): array
    {
        return [
            'status' => Response::HTTP_OK,
            'message' => __('auth::messages.auth_success'),
            'token' => $this->resource->plainTextToken,
        ];
    }
}
