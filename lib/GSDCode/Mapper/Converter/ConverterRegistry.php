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
 * Description of ConverterRegistry
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class ConverterRegistry implements ConverterRegistryInterface
{
    
    /**
     *
     * @var array a id => ConverterInterface array
     */
    private $converters = [];
    
    public function add($id, ConverterInterface $converter)
    {
        if($this->has($id))
        {
            throw new ConverterAlreadyExistsException();
        }
        
        $this->registry($id, $converter);
        return $this;
    }

    public function get($id)
    {
        if(!$this->has($id))
        {
            throw new ConverterNotFoundException("Unable to find a converter with id $id");
        }
        
        return $this->converters[$id];
    }

    public function has($id)
    {
        return array_key_exists($id, $this->converters);
    }

    public function remove($id)
    {
        if(!$this->has($id))
        {
            throw new ConverterNotFoundException("Unable to find a converter with id $id");
        }
        
        $this->unregistry($id);
        return $this;
    }
    
    public function search($classA, $classB)
    {
        
        foreach($this->converters as $converter)
        {
            if($converter->supports($classA, $classB))
            {
                return $converter;
            }
        }
        
        return null;
    }
    
    /**
     * Do the registration of a Converter
     * 
     * @param string $id
     * @param \GSDCode\Mapper\Converter\ConverterInterface $converter
     */
    private function registry($id, ConverterInterface $converter)
    {
        $this->converters[$id] = $converter;
    }
    
    /**
     * Do the un-registration of a Converter
     * 
     * @param string $id
     */
    private function unregistry($id)
    {
        unset($this->converters[$id]);
    }
    
}
