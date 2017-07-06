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

namespace Tests\GSDCode\Mapper\Converter\BuiltIn;

use GSDCode\Mapper\Converter\BuiltIn\StringToBoolConverter;
use GSDCode\Mapper\Converter\ConverterInterface;
use Tests\GSDCode\Mapper\BaseTestCase;

/**
 * Description of StringToBoolConverterTest
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class StringToBoolConverterTest extends BaseTestCase
{
    
    /**
     *
     * @var ConverterInterface
     */
    private $converter;
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->converter = new StringToBoolConverter();
    }
    
    public function testConvertFrom()
    {
        $compare = ["TRUE" => true, "1" => true, "FALSE" => false, "0" => false];
        foreach($compare as $before => $expected)
        {
            $this->assertEquals($expected, $this->converter->convert($before, "bool"));
        }
    }
    
    public function testConvertTo()
    {
        $compare = ["TRUE" => true, "FALSE" => false];
        foreach($compare as $expected => $before)
        {
            $this->assertEquals($expected, $this->converter->convert($before, "string"));
        }
    }
    
    /**
     * @expectedException \GSDCode\Mapper\Converter\ConverterException
     */
    public function testException()
    {
        $this->converter->convert("ANOTHER", "bool");
    }
    
}
