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

use GSDCode\Mapper\Converter\BuiltIn\DateTimeToIntConverter;
use GSDCode\Mapper\Converter\BuiltIn\DateTimeToStringConverter;
use GSDCode\Mapper\Converter\BuiltIn\IntToBoolConverter;
use GSDCode\Mapper\Converter\BuiltIn\StringToBoolConverter;
use GSDCode\Mapper\Converter\BuiltIn\StringToIntConverter;
use ReflectionClass;

/**
 * Description of ConverterRegistryLoader
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class ConverterRegistryLoader implements ConverterRegistryLoaderInterface
{
    
    public function load(array $config, ConverterRegistryInterface $registry)
    {
        foreach($config['converters'] as $converterRef)
        {
            $converter = $this->getConverterByRef($converterRef);
            $this->register($converterRef, $converter, $registry);
        }
        
        $this->loadDefaults($config, $registry);
    }
    
    protected function loadDefaults(array $config, ConverterRegistryInterface $registry)
    {
        $defaults = [
            StringToBoolConverter::class,
            StringToIntConverter::class,
            IntToBoolConverter::class,
            DateTimeToIntConverter::class
        ];
        
        foreach($defaults as $converterRef)
        {
            $this->register($converterRef, $this->getConverterByRef($converterRef), $registry);
        }
        
        $format = $config['settings']['date_format'];
        $this->register(DateTimeToStringConverter::class, new DateTimeToStringConverter($format), $registry);
    }
    
    protected function register($converterRef, ConverterInterface $converter, ConverterRegistryInterface $registry)
    {
        if($converter instanceof ConverterRegistryAware)
        {
            $converter->setRegistry($registry);
        }
        
        $registry->add($converterRef, $converter);
    }
    
    /**
     * Get the converter from the given ref
     * 
     * @param string $converterRef
     * @return ConverterInterface
     * @throws ConverterNotFoundException
     * @throw ConverterException
     */
    protected function getConverterByRef($converterRef)
    {
        if(!class_exists($converterRef))
        {
            throw new ConverterNotFoundException("Unable to find a converter identified by '$converterRef'");
        }
        
        $converter = (new ReflectionClass($converterRef))->newInstanceArgs();
        
        if(!($converter instanceof ConverterInterface))
        {
            throw new ConverterException("Converter '$converterRef' doesn't implement ConverterInterface");
        }
        
        return $converter;
    }

}
