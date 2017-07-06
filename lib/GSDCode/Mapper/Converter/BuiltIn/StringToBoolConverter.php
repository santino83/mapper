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

use GSDCode\Mapper\Converter\AbstractConverter;
use GSDCode\Mapper\Converter\ConverterException;

/**
 * Description of StringToBoolConverter
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class StringToBoolConverter extends AbstractConverter
{

    public function __construct()
    {
        parent::__construct("string", "bool");
    }

    protected function convertFrom($object, $classB)
    {
        $values = ["TRUE" => true, "1" => true, "FALSE" => false, "0" => false];
        
        $string = strtoupper($object);
        
        foreach($values as $compare => $return)
        {
            if(strval($compare) == $string)
            {
                return $return;
            }
        }
        
        throw new ConverterException("Unable to convert $object into a boolean");
    }

    protected function convertTo($object, $classA)
    {
        return $object === true ? "TRUE" : "FALSE";
    }

}
