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

namespace Whoa\l10n\Messages;

use Whoa\l10n\Contracts\Messages\BundleEncoderInterface;
use Whoa\l10n\Contracts\Messages\BundleStorageInterface;
use Whoa\l10n\Contracts\Messages\ResourceBundleInterface;

use function array_diff;
use function array_intersect;
use function array_keys;
use function assert;
use function in_array;

/**
 * @package Whoa\l10n
 */
class BundleEncoder implements BundleEncoderInterface
{
    /**
     * @var array
     */
    private array $bundles = [];

    /**
     * @inheritdoc
     */
    public function addBundle(ResourceBundleInterface $bundle): BundleEncoderInterface
    {
        $this->bundles[$bundle->getLocale()][$bundle->getNamespace()] = $bundle;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getStorageData(string $defaultLocale): array
    {
        $defaultNamespaces = $this->getNamespaces($defaultLocale);

        $data = [];
        foreach ($this->getLocales() as $locale) {
            $localizedNamespaces = $this->getNamespaces($locale);
            $combinedNamespaces = $defaultNamespaces + $localizedNamespaces;
            foreach ($combinedNamespaces as $namespace) {
                $bundle = $this->getBundle($locale, $namespace);
                $bundle = $bundle !== null ? $bundle : $this->getBundle($defaultLocale, $namespace);
                $data[$locale][$namespace] = $defaultLocale === $locale ? $this->encodeBundle($bundle) :
                    $this->encodeMergedBundles($bundle, $this->getBundle($defaultLocale, $namespace));
            }
        }

        return [
            BundleStorage::INDEX_DEFAULT_LOCALE => $defaultLocale,
            BundleStorage::INDEX_DATA => $data
        ];
    }

    /**
     * @return array
     */
    protected function getBundles(): array
    {
        return $this->bundles;
    }

    /**
     * @param ResourceBundleInterface $bundle
     * @return array
     */
    private function encodeBundle(ResourceBundleInterface $bundle): array
    {
        $encodedBundle = [];
        foreach ($bundle->getKeys() as $key) {
            $encodedBundle[$key] = $this->getBundleValue($bundle, (string)$key);
        }

        return $encodedBundle;
    }

    /**
     * @param string $locale
     * @param string $namespace
     * @return null|ResourceBundleInterface
     */
    private function getBundle(string $locale, string $namespace): ?ResourceBundleInterface
    {
        assert(empty($locale) === false && empty($namespace) === false);

        $bundles = $this->getBundles();
        $hasBundle = isset($bundles[$locale][$namespace]) === true;
        return $hasBundle === true ? $bundles[$locale][$namespace] : null;
    }

    /**
     * @param ResourceBundleInterface $localizedBundle
     * @param ResourceBundleInterface|null $defaultBundle
     * @return array
     */
    private function encodeMergedBundles(
        ResourceBundleInterface $localizedBundle,
        ResourceBundleInterface $defaultBundle = null
    ): array {
        if ($defaultBundle === null) {
            // there is no default bundle for this localized one
            return $this->encodeBundle($localizedBundle);
        }

        $localizedKeys = $localizedBundle->getKeys();
        $defaultKeys = $defaultBundle->getKeys();

        $commonKeys = array_intersect($localizedKeys, $defaultKeys);
        $localizedOnlyKeys = array_diff($localizedKeys, $commonKeys);
        $defaultOnlyKeys = array_diff($defaultKeys, $commonKeys);

        $encodedBundle = [];

        foreach ($commonKeys as $key) {
            $encodedBundle[$key] = $this->getBundleValue($localizedBundle, (string)$key);
        }
        foreach ($localizedOnlyKeys as $key) {
            $encodedBundle[$key] = $this->getBundleValue($localizedBundle, (string)$key);
        }
        foreach ($defaultOnlyKeys as $key) {
            $encodedBundle[$key] = $this->getBundleValue($defaultBundle, (string)$key);
        }

        return $encodedBundle;
    }

    /**
     * @return array
     */
    private function getLocales(): array
    {
        return array_keys($this->getBundles());
    }

    /**
     * @param string $locale
     * @return bool
     */
    private function hasLocale(string $locale): bool
    {
        assert(empty($locale) === false);

        return in_array($locale, $this->getLocales());
    }

    /**
     * @param string $locale
     * @return string[]
     */
    private function getNamespaces(string $locale): array
    {
        return $this->hasLocale($locale) === true ? array_keys($this->getBundles()[$locale]) : [];
    }

    /**
     * @param ResourceBundleInterface $bundle
     * @param string $key
     * @return string[]
     */
    private function getBundleValue(ResourceBundleInterface $bundle, string $key): array
    {
        return [
            BundleStorageInterface::INDEX_PAIR_VALUE => $bundle->getValue($key),
            BundleStorageInterface::INDEX_PAIR_LOCALE => $bundle->getLocale(),
        ];
    }
}
