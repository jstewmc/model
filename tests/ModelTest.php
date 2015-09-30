<?php

namespace Jstewmc\Model;

/**
 * A concrete test class
 */
class Foo extends Model
{
	protected static $properties = ['foo'];
}

/**
 * Tests for the Model class
 */
class ModelTest extends \PHPUnit_Framework_TestCase
{
	/* !__get() */
	
	/**
	 * __get() should throw OutOfBoundsException if $name is not a property
	 */
	public function test_get_throwsOutOfBoundsException_ifNameIsNotAProperty()
	{
		$this->setExpectedException('OutOfBoundsException');
		
		$model = new Foo();
		$model->bar;
		
		return;
	}
	
	/**
	 * __get() should return value if $name is a property
	 */
	public function test_get_getsProperty_ifNameIsAProperty()
	{
		$model = new Foo();
		$model->foo = 'bar';
		
		$this->assertEquals('bar', $model->foo);
		
		return;
	}
	 
	
	/* !__isset() */
	
	/**
	 * __isset() should return false if property does not exist
	 */
	public function test_isset_returnsFalse_ifPropertyDoesNotExist()
	{
		$model = new Foo();
		
		$this->assertFalse(isset($model->bar));
		
		return;
	}
	
	/**
	 * __isset() should return false if property is null
	 */
	public function test_isset_returnsFalse_ifPropertyIsNull()
	{
		$model = new Foo();
		$model->foo = null;
		
		$this->assertFalse(isset($model->foo));
		
		return;
	}
	
	/**
	 * __isset() should return true if property is not null
	 */
	public function test_isset_returnsTrue_ifPropertyIsNotNull()
	{
		$model = new Foo();
		$model->foo = 'bar';
		
		$this->assertTrue(isset($model->foo));
		
		return;
	}
	
	
	/* !__set() */
	
	/**
	 * __set() should throw OutOfBoundsException if property does not exist
	 */
	public function test_set_throwsOutOfBoundsException_ifPropertyDoesNotExist()
	{
		$this->setExpectedException('OutOfBoundsException');
		
		$model = new Foo();
		$model->bar = 'baz';
		
		return;
	}
	
	/**
	 * __set() should set property if property does exist
	 */
	public function test_set_setsProperty_ifPropertyDoesExist()
	{
		$model = new Foo();
		$model->foo = 'bar';
		
		$this->assertEquals('bar', $model->foo);
		
		return;
	}
	
	/* !__unset() */
	
	/**
	 * __unset() should throw OutOfBoundsException if property does not exist
	 */
	public function test_unset_throwsOutOfBoundsException_ifPropertyDoesNotExist()
	{
		$this->setExpectedException('OutOfBoundsException');
		
		$model = new Foo();
		unset($model->bar);
		
		return;
	}
	
	/**
	 * __unset() should unset property if property does exist
	 */
	public function test_unset_unsetsProperty_ifPropertyDoesExist()
	{
		$model = new Foo();
		$model->foo = 'bar';
		
		$this->assertEquals('bar', $model->foo);
		
		unset($model->foo);
		
		$this->assertNull($model->foo);
		
		return;
	}
	
	
	/* !hydate() */
	
	/**
	 * hydate() should set the model's properties
	 */
	public function test_hydrate_setsProperties()
	{
		$data = ['foo' => 'bar', 'baz' => 'qux'];
		
		$model = new Foo();
		$model->hydrate($data);
		
		$this->assertEquals('bar', $model->foo);
		$this->assertFalse(isset($model->baz));
		
		return;
	}
}
