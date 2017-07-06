<?php

/**
 * MIT License
 *
 * Copyright (c) 2017 Giorgio Maria Santini
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Tests\GSDCode\Mapper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;

/**
 * Description of BaseTestCase
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
abstract class BaseTestCase extends TestCase
{
    
    /**
     *
     * @var PropertyAccessorInterface
     */
    protected $propertyAccessor;
    
    /**
     *
     * @var PropertyInfoExtractorInterface
     */
    protected $propertyInfo;
    
    protected function setUp()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        
        // a full list of extractors is shown further below
        $phpDocExtractor = new PhpDocExtractor();
        $reflectionExtractor = new ReflectionExtractor();

        // array of PropertyListExtractorInterface
        $listExtractors = array($reflectionExtractor);

        // array of PropertyTypeExtractorInterface
        $typeExtractors = array($phpDocExtractor, $reflectionExtractor);

        // array of PropertyDescriptionExtractorInterface
        $descriptionExtractors = array($phpDocExtractor);

        // array of PropertyAccessExtractorInterface
        $accessExtractors = array($reflectionExtractor);
        
        $this->propertyInfo = new PropertyInfoExtractor($listExtractors, $typeExtractors, $descriptionExtractors, $accessExtractors);
    }
    
}
