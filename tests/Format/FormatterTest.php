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

declare(strict_types=1);

namespace Whoa\Tests\l10n\Format;

use Whoa\l10n\Format\Formatter;
use Whoa\l10n\Format\Translator;
use Whoa\l10n\Messages\BundleStorage;
use Whoa\l10n\Messages\FileBundleEncoder;
use PHPUnit\Framework\TestCase;

/**
 * @package Whoa\Tests\l10n
 */
class FormatterTest extends TestCase
{
    /**
     * Test translate.
     */
    public function testFormatter(): void
    {
        $storageData = (new FileBundleEncoder(null, TranslatorTest::RESOURCES_DIR))->getStorageData('en');
        $formatter = new Formatter('de', 'Messages', new Translator(new BundleStorage($storageData)));

        $this->assertEquals('Hallo Welt', $formatter->formatMessage('Hello World'));
        $this->assertEquals('Good morning', $formatter->formatMessage('Good morning'));
    }
}
