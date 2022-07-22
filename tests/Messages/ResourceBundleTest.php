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

namespace Whoa\Tests\l10n\Messages;

use Whoa\l10n\Messages\ResourceBundle;
use PHPUnit\Framework\TestCase;

/**
 * @package Whoa\Tests\l10n
 */
class ResourceBundleTest extends TestCase
{
    /**
     * Test basic get and set operations.
     */
    public function testGetAndSetStringKeys(): void
    {
        $bundle = new ResourceBundle('de_DE', 'ErrorMessages', [
            'Key as a readable text' => 'Lets assume it would be german translation',
            'key_as_an_id' => 'And that would be another german translation',
        ]);

        $this->assertEquals('de_DE', $bundle->getLocale());
        $this->assertEquals('ErrorMessages', $bundle->getNamespace());
        $this->assertEquals(['Key as a readable text', 'key_as_an_id'], $bundle->getKeys());
        $this->assertEquals('And that would be another german translation', $bundle->getValue('key_as_an_id'));
    }

    /**
     * Test basic get and set operations.
     */
    public function testGetAndSetIntKeys()
    {
        $bundle = new ResourceBundle('de_DE', 'ErrorMessages', [
            '0' => 'Lets assume it would be german translation',
            '1' => 'And that would be another german translation',
        ]);

        $this->assertEquals('de_DE', $bundle->getLocale());
        $this->assertEquals('ErrorMessages', $bundle->getNamespace());
        $this->assertSame([0, 1], $bundle->getKeys());
        $this->assertEquals('And that would be another german translation', $bundle->getValue('1'));
    }
}
