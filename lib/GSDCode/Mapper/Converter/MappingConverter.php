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

use Exception;
use GSDCode\Mapper\Bean\BeanFactoryInterface;
use GSDCode\Mapper\Util\TypeResolver;
use GSDCode\Mapper\Util\TypeUtil;

/**
 * Description of MappingConverter
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class MappingConverter implements ConverterInterface
{

    protected $mappings = [];

    /**
     *
     * @var ConverterRegistryInterface
     */
    protected $registry;

    /**
     *
     * @var BeanFactoryInterface
     */
    protected $beanFactory;

    /**
     *
     * @var TypeResolver
     */
    private $resolver;

    public function __construct(array $config, ConverterRegistryInterface $registry, BeanFactoryInterface $beanFactory, TypeResolver $resolver)
    {
        $mappings = $config['mappings'];

        $this->registry = $registry;
        $this->beanFactory = $beanFactory;
        $this->resolver = $resolver;

        $this->setUp($mappings);
    }

    public final function convert($object, $destinationClass)
    {
        return $this->checkObjectToConvert($object) ? $this->doConvert($object, get_class($object), $destinationClass) : null;
    }

    public final function supports($classA, $classB)
    {
        foreach ($this->getIdentifiers($classA, $classB) as $id) {
            if (array_key_exists($id, $this->mappings)) {
                return true;
            }
        }

        return false;
    }

    protected function doConvert($source, $sourceClass, $targetClass)
    {
        $target = $this->getTargetBean($targetClass);
        $mapping = $this->getMapping($sourceClass, $targetClass);

        //get source properties
        $properties = $this->resolver->getProperties($source);
        foreach ($properties as $property) {
            $this->convertProperty($property, $source, $sourceClass, $target, $mapping);
        }

        return $target;
    }

    protected function convertProperty($property, $source, $sourceClass, $target, MappingInfo $mapping)
    {
        $propertyValue = $this->resolver->getPropertyValue($source, $property);

        $context = $this->getConvertPropertyContext($property, $source, $target, $sourceClass, $mapping);

        if ($context['converter'] !== null) {
            $convertedProperty = $context['converter']->convert($propertyValue, $context['type-to-name']);
            $this->resolver->setPropertyValue($target, $context['write-to'], $convertedProperty);
            return;
        }

        if (TypeUtil::equals($context['type-from'], $context['type-to'])) {
            $this->resolver->setPropertyValue($target, $context['write-to'], $propertyValue);
            return;
        }

        $converter = $this->registry->search($context['type-from-name'], $context['type-to-name']);
        if ($converter === null) {
            $converter = $this;
        }

        if (!$converter->supports($context['type-from-name'], $context['type-to-name'])) {
            throw new ConverterException(sprintf("Unable to convert %s to %s", $context['type-from-name'], $context['type-to-name']));
        }

        $convertedProperty = $converter->convert($propertyValue, $context['type-to-name']);
        $this->resolver->setPropertyValue($target, $context['write-to'], $convertedProperty);
    }

    protected function getConvertPropertyContext($property, $source, $target, $sourceClass, MappingInfo $mapping)
    {
        $context = [
            //the name of the property on source that we must read
            'read-from' => $property,
            //the name of the property on target that we must write
            'write-to' => null,
            //the type of the property that we must read
            'type-from' => $this->resolver->getType($source, $property),
            //the name of the type-from
            'type-from-name' => null,
            //the type of the property that we must write
            'type-to' => null,
            //the name of the type-to
            'type-to-name' => null,
            //the converter to be used for conversion, if any
            'converter' => null
        ];

        $fieldMapping = $mapping->getFieldMapping($property, $sourceClass);
        
        //get target property name
        $context['write-to'] = null === $fieldMapping ?
                $property :
                ( $sourceClass == $mapping->getClassA() ? $fieldMapping->getFieldB() : $fieldMapping->getFieldA() );

        $context['type-to'] = $this->resolver->getType($target, $context['write-to']);
        $context['type-to-name'] = $context['type-to']->getBuiltinType() == 'object' ?
                $context['type-to']->getClassName() :
                $context['type-to']->getBuiltinType();

        $context['type-from-name'] = $context['type-from']->getBuiltinType() == 'object' ?
                $context['type-from']->getClassName() :
                $context['type-from']->getBuiltinType();

        if (null !== $fieldMapping && null !== $fieldMapping->getConverter()) {
            $context['converter'] = $fieldMapping->getConverter();
        }

        return $context;
    }

    protected function getTargetBean($targetClass)
    {
        try {
            return $this->beanFactory->createBean($targetClass);
        } catch (Exception $ex) {
            throw new ConverterException("Unable to create bean of class $targetClass", $ex);
        }
    }

    /**
     * 
     * @param string $classA
     * @param string $classB
     * @return MappingInfo
     * @throws ConverterException
     */
    protected function getMapping($classA, $classB)
    {
        foreach ($this->getIdentifiers($classA, $classB) as $id) {
            if (array_key_exists($id, $this->mappings)) {
                return $this->mappings[$id];
            }
        }

        throw new ConverterException("Unable to find mapping for class $classA <-> $classB ");
    }

    private function checkObjectToConvert($object)
    {

        if (!is_object($object)) {
            throw new ConverterException("Unable to convert object of type " . get_class($object));
        }

        return true;
    }

    private function setUp(Array $mappings = [])
    {
        foreach ($mappings as $mappingData) {
            $classA = $mappingData['classA'];
            $classB = $mappingData['classB'];
            $fields = $this->setupFields($mappingData['fields']);

            $mapping = new MappingInfo($classA, $classB, $fields);
            $this->registerMapping($mapping);
        }
    }

    private function setupFields(Array $fields = [])
    {
        $result = [];

        foreach ($fields as $fieldData) {
            $result[] = new MappingFieldInfo($fieldData['fieldA'], $fieldData['fieldB'], $fieldData['converter'] ? $this->registry->get($fieldData['converter']) : null);
        }

        return $result;
    }

    private function registerMapping(MappingInfo $mapping)
    {
        foreach ($this->getIdentifiers($mapping->getClassA(), $mapping->getClassB()) as $id) {
            $this->mappings[$id] = $mapping;
        }
    }

    private function getIdentifiers($classA, $classB)
    {
        return [md5("$classA+$classB"), md5("$classB+$classA")];
    }

}
