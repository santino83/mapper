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

namespace GSDCode\Mapper\Util;

use Symfony\Component\PropertyInfo\Type;

/**
 * Description of BuiltInTypeGuesser
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class BuiltInTypeGuesser implements TypeGuesser
{
    
    public function guess($value)
    {
        $types = ['null','string','int','float','bool','resource','callable','object','array'];
        
        foreach($types as $type)
        {
            $method = "is$type";
            if(($definition = $this->$method($value)) !== false)
            {
                return $definition;
            }
        }
        
        return null;
    }
    
    private function buildDefinition($type, $className = null, $collection = false, $collectionKeyType = null, $collectionValueType = null)
    {
        return new Type($type, true, $className, $collection, $collectionKeyType, $collectionValueType);        
    }
    
    private function isInt($value)
    {
        return null !== $value && is_int($value) ? $this->buildDefinition('int') : false;
    }
    
    private function isFloat($value)
    {
        return null !== $value && is_float($value) ? $this->buildDefinition('float') : false;
    }
    
    private function isString($value)
    {
        return null !== $value && is_string($value) ? $this->buildDefinition('string') : false;
    }
    
    private function isBool($value)
    {
        return null !== $value && is_bool($value) ? $this->buildDefinition('bool') : false;
    }
    
    private function isResource($value)
    {
        return null !== $value && is_resource($value) ? $this->buildDefinition('resource') : false;
    }
    
    private function isObject($value)
    {
        return null !== $value && is_object($value) ? $this->buildDefinition("object", get_class($value)) : false;
    }
    
    private function isNull($value)
    {
        return is_null($value) ? $this->buildDefinition('null') : false;
    }
    
    private function isArray($value)
    {
        if(null === $value || !is_array($value))
        {
            return false;
        }
        
        $keys = array_keys($value);
        $values = array_values($value);
        
        $keyType = count($keys) > 0 ? $this->guess($keys[0]) : null;
        $valueType = count($values) > 0 ? $this->guess($values[0]) : null;
        
        return $this->buildDefinition("array", null, true, $keyType, $valueType);        
    }
    
    private function isCallable($value)
    {
        return null !== $value && is_callable($value) ? $this->buildDefinition('callable') : false;
    }

}
