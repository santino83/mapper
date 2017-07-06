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

use DateTime;
use GSDCode\Mapper\Converter\BuiltIn\DateTimeToStringConverter;
use Tests\GSDCode\Mapper\BaseTestCase;

/**
 * Description of DateTimeToStringConverterTest
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class DateTimeToStringConverterTest extends BaseTestCase
{
    
    public function testConvertFrom()
    {
        $time = new DateTime("2016-01-01 15:18:00");
        
        $formats = [DateTime::ATOM,DateTime::COOKIE,DateTime::ISO8601,DateTime::RFC822,DateTime::RFC850,DateTime::RFC1036,
            DateTime::RFC1123,DateTime::RFC2822,DateTime::RFC3339,DateTime::RSS,DateTime::W3C,
            'Y-m-d', 'H:i:s'];
        
        foreach($formats as $format)
        {
            $converter = new DateTimeToStringConverter($format);
            $this->assertEquals($time->format($format), $converter->convert($time, "string"));
        }
    }
    
    public function testConvertTo()
    {
        $time = new DateTime("2016-01-01 15:18:00");
        
        $formats = [DateTime::ATOM,DateTime::COOKIE,DateTime::ISO8601,DateTime::RFC822,DateTime::RFC850,DateTime::RFC1036,
            DateTime::RFC1123,DateTime::RFC2822,DateTime::RFC3339,DateTime::RSS,DateTime::W3C,
            'Y-m-d', 'H:i:s'];
                
        foreach($formats as $format)
        {
            $converter = new DateTimeToStringConverter($format);
            $expected = $time->format($format);
            $this->assertEquals($expected, $converter->convert($expected, "DateTime")->format($format));
        }
    }
    
    /**
     * @expectedException \GSDCode\Mapper\Converter\ConverterException
     */
    public function testException()
    {
        $converter = new DateTimeToStringConverter();
        $converter->convert("99999999999999", "DateTime");
    }
    
}
