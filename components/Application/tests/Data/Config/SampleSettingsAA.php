<?php declare(strict_types=1);

namespace Limoncello\Tests\Application\Data\Config;

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

/**
 * @package Limoncello\Tests\Application
 */
class SampleSettingsAA extends SampleSettingsA implements MarkerInterfaceChild11, MarkerInterfaceChild11And21
{
    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @inheritdoc
     */
    public function get(array $appConfig): array
    {
        return ['value' => 'AA'] + parent::get($appConfig);
    }
}
