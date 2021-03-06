<?php

/**
 * Copyright 2015-2019 info@neomerx.com
 * Modification Copyright 2021-2022 info@whoaphp.com
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

declare (strict_types=1);

namespace Whoa\l10n\Contracts\Messages;

/**
 * @package Whoa\l10n
 */
interface BundleStorageInterface
{
    /** Encode index */
    public const INDEX_PAIR_VALUE = 0;

    /** Encode index */
    public const INDEX_PAIR_LOCALE = self::INDEX_PAIR_VALUE + 1;

    /**
     * @return string
     */
    public function getDefaultLocale(): string;

    /**
     * @param string $locale
     * @param string $namespace
     * @param string $key
     * @return bool
     */
    public function has(string $locale, string $namespace, string $key): bool;

    /**
     * @param string $locale
     * @param string $namespace
     * @param string $key
     * @return array|null
     */
    public function get(string $locale, string $namespace, string $key): ?array;

    /**
     * @param string $locale
     * @param string $namespace
     * @return bool
     */
    public function hasResources(string $locale, string $namespace): bool;

    /**
     * @param string $locale
     * @param string $namespace
     * @return array
     */
    public function getResources(string $locale, string $namespace): array;
}
