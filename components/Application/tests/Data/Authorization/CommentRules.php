<?php declare(strict_types=1);

namespace Limoncello\Tests\Application\Data\Authorization;

/**
 * Copyright 2015-2020 info@neomerx.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use Limoncello\Application\Contracts\Authorization\ResourceAuthorizationRulesInterface;
use Limoncello\Auth\Contracts\Authorization\PolicyInformation\ContextInterface;

/**
 * @package Limoncello\Tests\Application
 */
class CommentRules implements ResourceAuthorizationRulesInterface
{
    /** Action name */
    const ACTION_CAN_CREATE = 'canCreate';

    /**
     * @inheritdoc
     */
    public static function getResourcesType(): string
    {
        return 'comments';
    }

    /**
     * @param ContextInterface $context
     *
     * @return bool
     */
    public static function canCreate(ContextInterface $context): bool
    {
        assert($context);

        return true;
    }
}
