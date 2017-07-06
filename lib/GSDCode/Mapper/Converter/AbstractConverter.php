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

namespace GSDCode\Mapper\Converter;

/**
 * Description of AbstractConverter
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
abstract class AbstractConverter implements ConverterInterface
{
    
    private $classA;
    
    private $classB;
    
    public function __construct($classA, $classB)
    {
        $this->classA = $classA;
        $this->classB = $classB;
    }
    
    public function convert($object, $destinationClass)
    {
        return $destinationClass == $this->classA ? $this->convertTo($object, $destinationClass) : $this->convertFrom($object, $destinationClass);
    }
    
    protected abstract function convertFrom($object, $classB);
    
    protected abstract function convertTo($object, $classA);

    protected function getClassA()
    {
        return $this->classA;
    }

    protected function getClassB()
    {
        return $this->classB;
    }

    public function supports($classA, $classB)
    {
        return ( $this->classA == $classA && $this->classB == $classB ) ||
            ( $this->classA == $classB && $this->classB == $classA );
    }
    
}
