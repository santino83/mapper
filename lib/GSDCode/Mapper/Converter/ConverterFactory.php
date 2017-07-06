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

use BadMethodCallException;
use GSDCode\Mapper\Bean\BeanFactory;
use GSDCode\Mapper\Bean\BeanFactoryInterface;
use GSDCode\Mapper\Util\TypeResolver;

/**
 * Description of DefaultConverterFactory
 *
 * //TODO: removes setUp and exceeding setters/getters relying on ObjectMapperBuilder initialization
 * 
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
final class ConverterFactory
{
    
    /**
     *
     * @var array
     */
    private $config;
    
    /**
     *
     * @var ConverterRegistryInterface
     */
    private $converterRegistry;
    
    /**
     *
     * @var ConverterRegistryLoaderInterface
     */
    private $converterRegistryLoader;
    
    /**
     *
     * @var ConverterInterface
     */
    private $defaultConverter;
    
    /**
     *
     * @var BeanFactoryInterface
     */
    private $beanFactory;
    
    /**
     *
     * @var TypeResolver
     */
    private $typeResolver;
    
    /**
     *
     * @var boolean
     */
    private $loaded = false;
    
    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
    /**
     * 
     * @param mixed $objectA
     * @param string $classB
     * @return ConverterInterface
     * @throws ConverterNotFoundException if a valid converter cannot be found
     */
    public function getConverter($objectA, $classB)
    {
        if(!$this->loaded)
        {
            $this->setUp();
        }
        
        //TODO: do better
        $classA = is_object($objectA) ? get_class($objectA) : (new \GSDCode\Mapper\Util\BuiltInTypeGuesser())->guess($objectA)->getBuiltinType();
        
        //search for a converter that supports the given classes
        $converter = $this->converterRegistry->search($classA, $classB);
        
        if(null !== $converter)
        {
            return $converter;
        }
        
        if(!$this->defaultConverter->supports($classA, $classB))
        {
            throw new ConverterNotFoundException("Unable to find a converter to convert $classA to $classB");
        }
        
        return $this->defaultConverter;
    }
    
    /**
     * 
     * @throws BadMethodCallException
     */
    public function setUp()
    {
        if($this->loaded)
        {
            throw new BadMethodCallException("Factory already initialized, setUp called twice");
        }
        
        $this->loaded = true;
        
        $this->converterRegistry = null === $this->converterRegistry ? new ConverterRegistry() : $this->converterRegistry;
        $this->converterRegistryLoader = null === $this->converterRegistryLoader ? new ConverterRegistryLoader() : $this->converterRegistryLoader;
        $this->beanFactory = null === $this->beanFactory ? new BeanFactory() : $this->beanFactory;
        $this->typeResolver = null === $this->typeResolver ? TypeResolver::createWithDefaults() : $this->typeResolver;
        
        $this->defaultConverter = null === $this->defaultConverter ? 
                new MappingConverter($this->config, $this->converterRegistry, $this->beanFactory, $this->typeResolver) : 
                $this->defaultConverter;
        
        $this->converterRegistryLoader->load($this->config, $this->converterRegistry);
    }
    
    /**
     * 
     * @param ConverterRegistryInterface $converterRegistry
     * @return $this
     * @throws BadMethodCallException
     */
    public function setConverterRegistry(ConverterRegistryInterface $converterRegistry)
    {
        if($this->loaded)
        {
            throw new BadMethodCallException("Factory already initialized");
        }
        
        $this->converterRegistry = $converterRegistry;
        return $this;
    }

    /**
     * 
     * @param ConverterRegistryLoaderInterface $converterRegistryLoader
     * @return $this
     * @throws BadMethodCallException
     */
    public function setConverterRegistryLoader(ConverterRegistryLoaderInterface $converterRegistryLoader)
    {
        if($this->loaded)
        {
            throw new BadMethodCallException("Factory already initialized");
        }
        
        $this->converterRegistryLoader = $converterRegistryLoader;
        return $this;
    }
    
    /**
     * 
     * @param ConverterInterface $converter
     * @return $this
     * @throws BadMethodCallException
     */
    public function setDefaultConverter(ConverterInterface $converter)
    {
        if($this->loaded)
        {
            throw new BadMethodCallException("Factory already initialized");
        }
        
        $this->defaultConverter = $converter;
        return $this;
    }
    
    /**
     * 
     * @param BeanFactoryInterface $beanFactory
     * @return $this
     * @throws BadMethodCallException
     */
    public function setBeanFactory(BeanFactoryInterface $beanFactory)
    {
        if($this->loaded)
        {
            throw new BadMethodCallException("Factory already initialized");
        }
        
        $this->beanFactory = $beanFactory;
        return $this;
    }
    
    /**
     * 
     * @param TypeResolver $typeResolver
     * @return $this
     * @throws BadMethodCallException
     */
    public function setTypeResolver(TypeResolver $typeResolver)
    {
        if($this->loaded)
        {
            throw new BadMethodCallException("Factory already initialized");
        }
        
        $this->typeResolver = $typeResolver;
        return $this;
    }

    /**
     * 
     * @return ConverterRegistryInterface
     */
    public function getConverterRegistry()
    {
        return $this->converterRegistry;
    }

    /**
     * 
     * @return ConverterRegistryLoaderInterface
     */
    public function getConverterRegistryLoader()
    {
        return $this->converterRegistryLoader;
    }
    
    /**
     * 
     * @return ConverterInterface
     */
    public function getDefaultConverter()
    {
        return $this->defaultConverter;
    }
    
    /*+
     * @return \GSDCode\Mapper\Bean\BeanFactoryInterface
     */
    public function getBeanFactory()
    {
        return $this->beanFactory;
    }
    
    /**
     * 
     * @return TypeResolver
     */
    public function getTypeResolver()
    {
        return $this->typeResolver;
    }
    
}
