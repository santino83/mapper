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

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

/**
 * Description of TypeResolver
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class TypeResolver
{
    
    /**
     *
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;
    
    /**
     *
     * @var PropertyInfoExtractorInterface
     */
    private $propertyInfo;
    
    /**
     *
     * @var TypeGuesser
     */
    private $typeGuesser;
    
    /**
     * 
     * @param PropertyAccessorInterface $propertyAccessor
     * @param PropertyInfoExtractorInterface $propertyInfo
     */
    public function __construct(PropertyAccessorInterface $propertyAccessor, PropertyInfoExtractorInterface $propertyInfo, TypeGuesser $typeGuesser = null)
    {
        $this->propertyAccessor = $propertyAccessor;
        $this->propertyInfo = $propertyInfo;
        $this->typeGuesser = null === $typeGuesser ? new BuiltInTypeGuesser() : $typeGuesser;
    }

    /**
     * Get the object's property names
     * 
     * @param mixed $object
     * @return array of property names
     */
    public function getProperties($object)
    {
        return $this->propertyInfo->getProperties(get_class($object));
    }
    
    /**
     * 
     * @param mixed $object
     * @param string $property
     * @return mixed
     * @throws TypeException if property cannot be read from object
     */
    public function getPropertyValue($object, $property)
    {
        if(!$this->propertyAccessor->isReadable($object, $property))
        {
            throw new TypeException("Unable to read property $property from object ".get_class($object));
        }
        
        return $this->propertyAccessor->getValue($object, $property);
    }
    
    /**
     * 
     * @param mixed $object
     * @param string $property
     * @param mixed $value
     * @throws TypeException if property cannot be written into object
     */
    public function setPropertyValue($object, $property, $value)
    {
        if(!$this->propertyAccessor->isWritable($object, $property))
        {
            throw new TypeException("Unable to write property $property into object ".get_class($object));
        }
        
        $this->propertyAccessor->setValue($object, $property, $value);
    }
    
    /**
     * Get the type of a property
     * 
     * @param mixed $object
     * @param string $property
     * @return Type
     * @throws TypeException if property type cannot be determined
     */
    public function getType($object, $property)
    {
        
        $class = get_class($object);
        $types = $this->propertyInfo->getTypes($class, $property);
        
        if(null === $types)
        {
            return $this->guessType($object, $property);
        }
                
        if(count($types) > 1)
        {
            throw new TypeException("Property $property cannot be of different types");
        }
        
        return $types[0];
    }
    
    /**
     * Try to guess the property type by its current property value
     * 
     * @param mixed $object
     * @param string $property
     * @return Type the property type
     * @throws TypeException if property type cannot be guess
     */
    private function guessType($object, $property)
    {        
        $value = $this->getPropertyValue($object, $property);
        
        $guessed = $this->typeGuesser->guess($value);
        
        if($guessed->getBuiltinType() == 'null')
        {
            throw new TypeException("Unable to use 'null' property type for property $property");
        }
        
        return $guessed;
    }
    
    public static function createWithDefaults()
    {        
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
        
        $propertyInfo = new PropertyInfoExtractor($listExtractors, $typeExtractors, $descriptionExtractors, $accessExtractors);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $typeGuesser = new BuiltInTypeGuesser();
        
        return new TypeResolver($propertyAccessor, $propertyInfo, $typeGuesser);
    }
    
}
