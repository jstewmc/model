<?php

namespace Jstewmc\Model;

/**
 * A model
 * 
 * @author     Jack Clayton
 * @copyright  2015 Jack Clayton
 * @license    MIT
 * @since      0.1.0
 */
abstract class Model
{
	/* !Protected properties */
	
	/**
	 * @var  mixed[]  the model's data indexed by property name
	 * @since  0.1.0
	 */
	protected $data = [];
		
	/**
	 * @var  string[]  an array of the model's properties
	 * @since  0.1.0
	 */
	protected static $properties = [];
	
	
	/* !Magic methods */
	
	/**
	 * Called when the model is constructed
	 *
	 * @return  self
	 * @since  0.1.0
	 */
	public function __construct()
	{
		// loop through the model's properties
		foreach (static::$properties as $property) {
			// initialize each property to null
			$this->data[$property] = null;
		}
		
		return;
	}
	
	/**
	 * Called when reading data from an inaccessible property
	 *
	 * I'm null value sensitive. I'll throw an OutOfBoundsException if the property 
	 * name does not exist. However, if the property exists and has a null value, 
	 * I'll return null.
	 *
	 * @return  mixed
	 * @throws  OutOfBoundsException  if $name is not a valid property name
	 * @since  0.1.0
	 */
	public function __get($name)
	{
		if ( ! in_array($name, static::$properties)) {
			throw new \OutOfBoundsException("Property '$name' does not exist");
		}
		
		return $this->data[$name];
	}
	
	/**
	 * Called when calling empty() or isset() on inaccessible property
	 *
	 * @return  bool
	 * @since  0.1.0
	 */
	public function __isset($name)
	{
		return in_array($name, static::$properties) && isset($this->data[$name]);
	}
	
	/**
	 * Called when writing data to an inaccessible property
	 *
	 * Keep in mind, the return value of __set() is ignored because of the way PHP
	 * processes the assignment operator. 
	 *
	 * See http://php.net/manual/en/language.oop5.overloading.php#object.set for 
	 * details. 
	 *
	 * @param  string  $name  the property's name
	 * @param  mixed   $value  the property's value
	 * @return  void  
	 * @throws  OutOfBoundsException  if $name is not a property name
	 * @since  0.1.0
	 */
	public function __set($name, $value)
	{
		if ( ! in_array($name, static::$properties)) {
			throw new \OutOfBoundsException("Property '$name' does not exist");
		}
		
		$this->data[$name] = $value;
		
		return;
	}
	
	/**
	 * Called when unset() is used on inaccessible property
	 *
	 * @return  void
	 * @throws  OutOfBoundsException  if $name is not a property name
	 * @since  0.1.0
	 */
	public function __unset($name)
	{
		if ( ! in_array($name, static::$properties)) {
			throw new \OutOfBoundsException("Property '$name' does not exist");
		}
		
		$this->data[$name] = null;
		
		return;
	}
	
	/**
	 * Hydrates the model's data
	 *
	 * Obviously, this introduces security issues if a model has properties a user
	 * should not access.
	 * 
	 * @param  mixed[]  $data  the model's new data indexed by property name
	 * @return  self
	 * @since  0.1.0
	 */
	public function hydrate(Array $data)
	{
		$this->data = array_intersect_key($data, array_flip(static::$properties));
		
		return $this;
	}
}
