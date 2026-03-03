<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\ListingChangeProposal;

use App\V1\Core\Application\Providers\ModuleServiceProvider;

class ListingChangeProposalModuleServiceProvider extends ModuleServiceProvider
{
    public const MODULE_NAME = 'listing-change-proposal';

    public function moduleName(): string
    {
        return self::MODULE_NAME;
    }

}
