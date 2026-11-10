<?php

use Behat\Config\Config;
use Behat\Config\Filter\TagExpressionFilter;
use Behat\Config\Profile;
use Behat\Config\Suite;
use Features\Bootstrap\AppContext;

return (new Config)
    ->withProfile(
        (new Profile('default'))
            ->withSuite(
                (new Suite('default'))
                    ->withPaths('%paths.base%/features')
                    ->withContexts(AppContext::class)
                    ->withFilter(new TagExpressionFilter('not @planned'))
            )
    );
