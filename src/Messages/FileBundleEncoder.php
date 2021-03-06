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

use Whoa\Contracts\L10n\MessageStorageInterface;
use Whoa\l10n\Contracts\Messages\ResourceBundleInterface;

use function assert;
use function class_exists;
use function class_implements;
use function glob;
use function in_array;
use function is_dir;
use function is_string;
use function pathinfo;
use function realpath;
use function scandir;

/**
 * @package Whoa\l10n
 */
class FileBundleEncoder extends BundleEncoder
{
    /**
     * @var string
     */
    private string $globMessagePatterns;

    /**
     * @param iterable|null $messageDescriptions
     * @param string $localesDir
     * @param string $globMessagePatterns
     */
    public function __construct(
        ?iterable $messageDescriptions,
        string $localesDir,
        string $globMessagePatterns = '*.php'
    ) {
        $this
            ->setGlobMessagePatterns($globMessagePatterns)
            ->loadDescriptions($messageDescriptions)
            ->loadBundles($localesDir);
    }

    /**
     * Method is used for loading resources from packages.
     * @param iterable|null $messageDescriptions
     * @return FileBundleEncoder
     * @see ProvidesMessageResourcesInterface
     *
     */
    protected function loadDescriptions(?iterable $messageDescriptions): self
    {
        if ($messageDescriptions !== null) {
            foreach ($messageDescriptions as [$locale, $namespace, $messageStorage]) {
                assert(is_string($locale) === true && empty($locale) === false);
                assert(is_string($namespace) === true && empty($namespace) === false);
                assert(is_string($messageStorage) === true && empty($messageStorage) === false);
                assert(class_exists($messageStorage) === true);
                assert(in_array(MessageStorageInterface::class, class_implements($messageStorage)) === true);

                /** @var MessageStorageInterface $messageStorage */

                $properties = $messageStorage::getMessages();
                $bundle = new ResourceBundle($locale, $namespace, $properties);
                $this->addBundle($bundle);
            }
        }

        return $this;
    }

    /**
     * @param string $localesDir
     * @return self
     */
    protected function loadBundles(string $localesDir): self
    {
        assert(empty($localesDir) === false);

        $localesDir = realpath($localesDir);
        assert($localesDir !== false);

        foreach (scandir($localesDir) as $fileOrDir) {
            if ($fileOrDir !== '.' && $fileOrDir !== '..' &&
                is_dir($localeDirFullPath = $localesDir . DIRECTORY_SEPARATOR . $fileOrDir . DIRECTORY_SEPARATOR)
            ) {
                $localeDir = $fileOrDir;
                foreach (glob($localeDirFullPath . $this->getGlobMessagePatterns()) as $messageFile) {
                    $namespace = pathinfo($messageFile, PATHINFO_FILENAME);
                    $bundle = $this->loadBundleFromFile($messageFile, $localeDir, $namespace);
                    $this->addBundle($bundle);
                }
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getGlobMessagePatterns(): string
    {
        return $this->globMessagePatterns;
    }

    /**
     * @param string $globMessagePatterns
     * @return self
     */
    protected function setGlobMessagePatterns(string $globMessagePatterns): self
    {
        assert(is_string($globMessagePatterns) === true && empty($globMessagePatterns) === false);
        $this->globMessagePatterns = $globMessagePatterns;

        return $this;
    }

    /**
     * @param string $fileFullPath
     * @param string $localeDir
     * @param string $messageFile
     * @return ResourceBundleInterface
     */
    protected function loadBundleFromFile(
        string $fileFullPath,
        string $localeDir,
        string $messageFile
    ): ResourceBundleInterface {
        $properties = require $fileFullPath;
        return new ResourceBundle($localeDir, $messageFile, $properties);
    }
}
