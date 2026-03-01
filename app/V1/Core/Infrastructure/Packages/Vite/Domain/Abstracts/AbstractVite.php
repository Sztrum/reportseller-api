<?php

declare(strict_types=1);

namespace App\V1\Core\Infrastructure\Packages\Vite\Domain\Abstracts;

use Illuminate\Foundation\Vite;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class AbstractVite extends Vite
{
    /**
     * @param string|array<int, string> $entrypoints
     */
    public function __invoke($entrypoints, $buildDirectory = null)
    {
        // Keep original Vite behavior; only customize hot file path by build directory.
        $this->prepareHotFilePath($buildDirectory);

        /** @var HtmlString $result */
        $result = parent::__invoke($entrypoints, $buildDirectory);

        return $result;
    }

    private function prepareHotFilePath(?string $buildDirectory): void
    {
        if ($buildDirectory) {
            $this->hotFile = public_path('/hot-' . Str::slug($buildDirectory));
        }
    }
}
