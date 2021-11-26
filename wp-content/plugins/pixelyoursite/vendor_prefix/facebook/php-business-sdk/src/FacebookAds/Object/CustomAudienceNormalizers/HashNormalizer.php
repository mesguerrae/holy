<?php

/**
 * Copyright (c) 2014-present, Facebook, Inc. All rights reserved.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * Facebook.
 *
 * As with any software that integrates with the Facebook platform, your use
 * of this software is subject to the Facebook Developer Principles and
 * Policies [http://developers.facebook.com/policy/]. This copyright notice
 * shall be included in all copies or substantial portions of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */
namespace PYS_PRO_GLOBAL\FacebookAds\Object\CustomAudienceNormalizers;

use PYS_PRO_GLOBAL\FacebookAds\Object\CustomAudienceNormalizers\ValueNormalizerInterface;
use PYS_PRO_GLOBAL\FacebookAds\Object\Fields\CustomAudienceMultikeySchemaFields;
class HashNormalizer implements \PYS_PRO_GLOBAL\FacebookAds\Object\CustomAudienceNormalizers\ValueNormalizerInterface
{
    /**
     *  @var string
     */
    const HASH_TYPE_SHA256 = 'sha256';
    /**
     * @param string $key
     * @param string $key_value
     * @return boolean
     */
    public function shouldNormalize($key, $key_value)
    {
        return $key !== \PYS_PRO_GLOBAL\FacebookAds\Object\Fields\CustomAudienceMultikeySchemaFields::LOOKALIKE_VALUE;
    }
    /**
     * @param string $key
     * @param string $key_value
     * @return string
     */
    public function normalize($key, $key_value)
    {
        return \hash(self::HASH_TYPE_SHA256, $key_value);
    }
}
