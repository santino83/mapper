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

namespace GSDCode\Mapper;

use GSDCode\Mapper\Bean\BeanFactory;
use GSDCode\Mapper\Bean\BeanFactoryInterface;
use GSDCode\Mapper\Converter\ConverterFactory;
use GSDCode\Mapper\Converter\ConverterInterface;
use GSDCode\Mapper\Converter\ConverterRegistry;
use GSDCode\Mapper\Converter\ConverterRegistryInterface;
use GSDCode\Mapper\Converter\ConverterRegistryLoader;
use GSDCode\Mapper\Converter\ConverterRegistryLoaderInterface;
use GSDCode\Mapper\Converter\MappingConverter;
use GSDCode\Mapper\Util\BuiltInTypeGuesser;
use GSDCode\Mapper\Util\TypeGuesser;
use GSDCode\Mapper\Util\TypeResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;

/**
 * Description of ObjectMapperBuilder
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
final class ObjectMapperBuilder
{
    
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
     * @var TypeGuesser
     */
    private $typeGuesser;
    
    /**
     *
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;
    
    /**
     *
     * @var PropertyInfoExtractorInterface
     */
    private $propertyInfoExtractor;
    
    /**
     * Get a new Mapper instance
     * 
     * @param array $config the Mapper Configuration
     * @return Mapper
     */
    public function build(array $config)
    {
        return new ObjectMapper($this->buildConverterFactory($config));
    }
    
    /**
     * 
     * @param array $config
     * @return ConverterFactory
     */
    private function buildConverterFactory(array $config)
    {
        $factory = new ConverterFactory($config);
        
        $this->converterRegistry = $this->buildConverterRegistry();
        $this->converterRegistryLoader = $this->buildConverterRegistryLoader();
        $this->beanFactory = $this->buildBeanFactory();
        $this->propertyAccessor = $this->buildPropertyAccessor();
        $this->propertyInfoExtractor = $this->buildPropertyInfoExtractor();
        $this->typeGuesser = $this->buildTypeGuesser();
        $this->typeResolver = $this->buildTypeResolver();
        $this->defaultConverter = $this->buildDefaultConverter($config);
        
        $factory->setBeanFactory($this->beanFactory)
                ->setConverterRegistry($this->converterRegistry)
                ->setConverterRegistryLoader($this->converterRegistryLoader)
                ->setDefaultConverter($this->defaultConverter)
                ->setTypeResolver($this->typeResolver)
                ->setUp();
        
        return $factory;
    }
    
    /**
     * 
     * @param ConverterRegistryInterface $converterRegistry
     * @return $this
     */
    public function withConverterRegistry(ConverterRegistryInterface $converterRegistry)
    {
        $this->converterRegistry = $converterRegistry;
        return $this;
    }
    
    /**
     * 
     * @return ConverterRegistryInterface
     */
    private function buildConverterRegistry()
    {
        return $this->converterRegistry === null ? new ConverterRegistry() : $this->converterRegistry;
    }
    
    /**
     * 
     * @param ConverterRegistryLoaderInterface $converterRegistryLoader
     * @return $this
     */
    public function withConverterRegistryLoader(ConverterRegistryLoaderInterface $converterRegistryLoader)
    {
        $this->converterRegistryLoader = $converterRegistryLoader;
        return $this;
    }
    
    /**
     * 
     * @return ConverterRegistryLoaderInterface
     */
    private function buildConverterRegistryLoader()
    {
        return $this->converterRegistryLoader === null ? new ConverterRegistryLoader() : $this->converterRegistryLoader;
    }
    
    /**
     * 
     * @param ConverterInterface $defaultConverter
     * @return $this
     */
    public function withDefaultConverter(ConverterInterface $defaultConverter)
    {
        $this->defaultConverter = $defaultConverter;
        return $this;
    }
    
    /**
     * 
     * @param array $config
     * @return ConverterInterface
     */
    private function buildDefaultConverter(array $config)
    {
        return $this->defaultConverter !== null ? $this->defaultConverter : 
                new MappingConverter($config, $this->converterRegistry, $this->beanFactory, $this->typeResolver);
    }
    
    /**
     * 
     * @param BeanFactoryInterface $beanFactory
     * @return $this
     */
    public function withBeanFactory(BeanFactoryInterface $beanFactory)
    {
        $this->beanFactory = $beanFactory;
        return $this;
    }
    
    /**
     * 
     * @return BeanFactoryInterface
     */
    private function buildBeanFactory()
    {
        return $this->beanFactory === null ? new BeanFactory() : $this->beanFactory;
    }
    
    /**
     * 
     * @param TypeResolver $typeResolver
     * @return $this
     */
    public function withTypeResolver(TypeResolver $typeResolver)
    {
        $this->typeResolver = $typeResolver;
        return $this;
    }
    
    /**
     * 
     * @return TypeResolver
     */
    private function buildTypeResolver()
    {
        return $this->typeResolver === null ? new TypeResolver($this->propertyAccessor, $this->propertyInfoExtractor, $this->typeGuesser) :
            $this->typeResolver;
    }
    
    /**
     * 
     * @param TypeGuesser $typeGuesser
     * @return $this
     */
    public function withTypeGuesser(TypeGuesser $typeGuesser)
    {
        $this->typeGuesser = $typeGuesser;
        return $this;
    }
    
    /**
     * 
     * @return TypeGuesser
     */
    private function buildTypeGuesser()
    {
        return $this->typeGuesser === null ? new BuiltInTypeGuesser() : $this->typeGuesser;
    }
    
    /**
     * 
     * @param PropertyAccessorInterface $propertyAccessor
     * @return $this
     */
    public function withPropertyAccessor(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
        return $this;
    }
    
    /**
     * 
     * @return PropertyAccessorInterface
     */
    private function buildPropertyAccessor()
    {
        return $this->propertyAccessor === null ? PropertyAccess::createPropertyAccessor() :
            $this->propertyAccessor;
    }
    
    /**
     * 
     * @param PropertyInfoExtractorInterface $propertyInfoExtractor
     * @return $this
     */
    public function withPropertyInfoExtractor(PropertyInfoExtractorInterface $propertyInfoExtractor)
    {
        $this->propertyInfoExtractor = $propertyInfoExtractor;
        return $this;
    }
    
    /**
     * 
     * @return PropertyInfoExtractorInterface
     */
    private function buildPropertyInfoExtractor()
    {
        if($this->propertyInfoExtractor !== null)
        {
            return $this->propertyInfoExtractor;
        }
        
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
        
        return new PropertyInfoExtractor($listExtractors, $typeExtractors, $descriptionExtractors, $accessExtractors);
    }
    
}
