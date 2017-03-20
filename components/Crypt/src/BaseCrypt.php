<?php namespace Limoncello\Crypt;

/**
 * Copyright 2015-2016 info@neomerx.com (www.neomerx.com)
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
 * @package Limoncello\Crypt
 */
abstract class BaseCrypt
{
    /**
     * @return void
     */
    protected function clearErrors()
    {
        while ($this->openSslErrorString() !== false) {
            // drop all accumulated error messages if any
        }
    }

    /**
     * @return null|string
     */
    protected function getErrorMessage()
    {
        $message = null;
        while (($errorLine = $this->openSslErrorString()) !== false) {
            $message .= ($message === null ? $errorLine : PHP_EOL . $errorLine);
        }

        return $message;
    }

    /**
     * @param CryptException $exception
     */
    protected function throwException(CryptException $exception)
    {
        throw $exception;
    }

    /**
     * We need this wrapper for testing purposes so we can mock system call to Open SSL.
     *
     * @return string|false
     */
    protected function openSslErrorString()
    {
        return openssl_error_string();
    }
}