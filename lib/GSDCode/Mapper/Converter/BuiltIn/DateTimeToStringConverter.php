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

namespace GSDCode\Mapper\Converter\BuiltIn;

use DateTime;
use Exception;
use GSDCode\Mapper\Converter\AbstractConverter;
use GSDCode\Mapper\Converter\ConverterException;

/**
 * Description of DateTimeToStringConverter
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class DateTimeToStringConverter extends AbstractConverter
{

    /**
     *
     * @var string
     */
    private $format;

    public function __construct($format = DateTime::W3C)
    {
        parent::__construct(DateTime::class, "string");

        $this->format = $format;
    }

    protected function convertFrom($object, $classB)
    {
        /* @var $object DateTime */
        
        $result = $object->format($this->format);
        
        if($result === false)
        {
            throw new ConverterException("Unable to convert date to string with the format " . $this->format);
        }
        
        return $result;
    }

    protected function convertTo($object, $classA)
    {
        $result = DateTime::createFromFormat($this->format, $object);
        
        if($result === false)
        {
            throw new ConverterException("Unable to convert $object to DateTime with format " . $this->format);
        }
        
        return $result;
    }

}
