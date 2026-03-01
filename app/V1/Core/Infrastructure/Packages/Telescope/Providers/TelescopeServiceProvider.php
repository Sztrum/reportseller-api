<?php

declare(strict_types=1);

namespace App\V1\Core\Infrastructure\Packages\Telescope\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Laravel\Telescope\Contracts\EntriesRepository;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\IncomingExceptionEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //         Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
            if ($entry instanceof IncomingExceptionEntry) {
                //                                dd($entry);
            }

            return true;
        });
    }

    public function isJob(IncomingEntry $entry, string $jobClass = null): bool
    {
        if ($entry->type === EntryType::JOB) {
            if (!$jobClass) {
                return true;
            }

            return ($entry->content['name'] ?? null) === $jobClass;
        }

        return false;
    }

    public function isWebsocketRequest(IncomingEntry $entry): bool
    {
        if ($this->isRequest($entry, 'POST')) {
            return Str::startsWith($this->entryUri($entry), '/broadcasting');
        }

        return false;
    }

    public function isApiRequest(IncomingEntry $entry): bool
    {
        return Str::startsWith($this->entryUri($entry), '/api');
    }

    public function isEvent(IncomingEntry $entry, string $eventClass = null): bool
    {
        if ($entry->type === 'event') {
            if (!$eventClass) {
                return true;
            }

            return ($entry->content['name'] ?? null) === $eventClass;
        }

        return false;
    }

    public function isSuccessRequest(IncomingEntry $entry): bool
    {
        return $entry->type === EntryType::REQUEST && $this->entryResponseStatus($entry) === 200;
    }

    public function isFailedRequest(IncomingEntry $entry): bool
    {
        $responseStatus = $this->entryResponseStatus($entry);

        return $entry->type === EntryType::REQUEST && $responseStatus >= 500 && $responseStatus !== 503;
    }

    public function isFailedJob(IncomingEntry $entry): bool
    {
        return $entry->type === EntryType::JOB
            && ($entry->content['status'] ?? null) === 'failed';
    }

    public function isProcessedJob(IncomingEntry $entry): bool
    {
        return $entry->type === EntryType::JOB
            && ($entry->content['status'] ?? null) === 'processed';
    }

    public function isScheduledTask(IncomingEntry $entry): bool
    {
        return $entry->type === EntryType::SCHEDULED_TASK;
    }

    public function hasMonitoredTag(IncomingEntry $entry): bool
    {
        if (!empty($entry->tags)) {
            return app(EntriesRepository::class)->isMonitoring($entry->tags);
        }

        return false;
    }

    public function isException(IncomingEntry $entry): bool
    {
        return $entry->type === EntryType::EXCEPTION;
    }

    public function isRequestWithStatus(IncomingEntry $entry, string $method = null, int $status = null): bool
    {
        if ($entry->type === EntryType::REQUEST) {
            if ($method && $status) {
                return ($entry->content['method'] ?? null) === $method && ($entry->content['response_status'] ?? 200) === $status;
            }

            if ($method) {
                return ($entry->content['method'] ?? null) === $method;
            }

            if ($status) {
                return ($entry->content['response_status'] ?? 200) === $status;
            }

            return true;
        }

        return false;
    }

    public function isRequest(IncomingEntry $entry, string $method = null): bool
    {
        if ($entry->type === EntryType::REQUEST) {
            if ($method) {
                return ($entry->content['method'] ?? null) === $method;
            }

            return true;
        }

        return false;
    }

    public function isLivewireRequest(IncomingEntry $entry, string $method = 'POST'): bool
    {
        if ($this->isRequest($entry, $method)) {
            return Str::startsWith($this->entryUri($entry), '/livewire/');
        }

        return false;
    }

    public function isLivewireFailedRequest(IncomingEntry $entry, string $method = 'POST'): bool
    {
        return $this->isLivewireRequest($entry, $method) && $this->isFailedRequest($entry);
    }

    public function isLoginRequest(IncomingEntry $entry, string $method = 'POST'): bool
    {
        if ($this->isRequest($entry, $method)) {
            $uri = $this->entryUri($entry);

            return Str::startsWith($uri, '/login') || (Str::startsWith($uri, '/2fa/') && Str::endsWith($uri, '/login'));
        }

        return false;
    }

    public function isLogoutRequest(IncomingEntry $entry, string $method = 'POST'): bool
    {
        if ($this->isRequest($entry, $method)) {
            return Str::startsWith($this->entryUri($entry), '/logout');
        }

        return false;
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', static function (mixed $user): bool {
            if (!is_object($user)) {
                return false;
            }

            $roleId = data_get($user, 'role_id');

            return $roleId === 1 || $roleId === 2;
        });
    }

    private function entryUri(IncomingEntry $entry): string
    {
        $uri = $entry->content['uri'] ?? null;

        return is_string($uri) ? $uri : '';
    }

    private function entryResponseStatus(IncomingEntry $entry): int
    {
        $responseStatus = $entry->content['response_status'] ?? null;

        return is_int($responseStatus) ? $responseStatus : 0;
    }
}
