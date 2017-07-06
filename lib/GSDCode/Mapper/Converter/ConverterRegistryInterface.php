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
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
interface ConverterRegistryInterface
{
    
    /**
     * Get a converter by the given id
     * 
     * @param string $id
     * @return ConverterInterface
     * @throws ConverterNotFoundException if converter doesn't exist
     */
    function get($id);
    
    /**
     * Get a converter that supports $classA/$classB conversion. Return null if converter cannot be found
     * 
     * @param string $classA
     * @param string $classB
     * @return ConverterInterface|null
     */
    function search($classA, $classB);
    
    /**
     * Add a converter with the given id
     * 
     * @param type $id
     * @param ConverterInterface $converter
     * @return $this
     * @throw ConverterAlreadyExistsException if a converter with the same id already exists
     * @throw ConverterException if converter cannot be registered
     */
    function add($id, ConverterInterface $converter);
    
    /**
     * Remove a converter by the given id
     * 
     * @param string $id
     * @return $this
     * @throws ConverterNotFoundException if converter doesn't exist
     */
    function remove($id);
    
    /**
     * Check if a converter exists by the given id
     * 
     * @param string $id
     * @return boolean 
     */
    function has($id);
    
}
