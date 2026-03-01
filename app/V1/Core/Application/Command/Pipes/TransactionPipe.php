<?php

declare(strict_types=1);

namespace App\V1\Core\Application\Command\Pipes;

use Illuminate\Database\ConnectionResolverInterface;
use Throwable;

class TransactionPipe
{
    public function __construct(
        private ConnectionResolverInterface $connectionResolver
    ) {
    }

    /**
     * @param mixed $job
     * @param callable(mixed): mixed $next
     * @throws Throwable
     * @return mixed
     */
    public function handle(mixed $job, callable $next): mixed
    {
        return $this->connectionResolver->connection()
            ->transaction(fn () => $next($job));
    }
}
