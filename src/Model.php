<?php

namespace Jstewmc\Model;

/**
 * An active resource model
 * 
 * @author     Jack Clayton
 * @copyright  2015 Jack Clayton
 * @license    MIT
 * @since      0.1.0
 */
class Model
{
	/* !Protected properties */
	
	/**
	 * @var  Api\Client  the model's API client
	 * @since  0.1.0
	 */
	protected $client;
	
	/**
	 * @var  mixed[]  the model's data indexed by property name
	 * @since  0.1.0
	 */
	protected $data = [];
	
	/**
	 * @var  string  the model's url host (e.g., "example.com")
	 * @since  0.1.0
	 */
	protected static $host;
	
	/**
	 * @var  string  the model's url path (the model's id will be appended to the 
	 *     path for read, update, and delete operations, e.g., "path/to/model/{id}")
	 * @since  0.1.0
	 */
	protected static $path;
	
	/**
	 * @var  string[]  an array of the model's properties ("id" is a required 
	 *     property) (defaults to ["id"])
	 * @since  0.1.0
	 */
	protected static $properties = ['id'];
	
	/**
	 * @var  string  the model's url scheme (defaults to "http") (accepted values are
	 *     "http" and "https")
	 * @since  0.1.0
	 */
	protected static $scheme = 'http';
	
	
	
	/* !Magic methods */
	
	/**
	 * Called when the model is constructed
	 *
	 * @param  Jstewmc\Model\Api\Client  $client  the API client to use
	 * @return  self
	 * @since  0.1.0
	 */
	public function __construct(Api\Client $client)
	{
		// if the required properties have not been set, short-circuit
		if (
			! strlen(static::$scheme) 
			|| ! strlen(static::$host) 
			|| ! strlen(static::$path)
			|| ! count(static::$properties)
		) {
			throw new \BadMethodCallException(
				__METHOD__."() expects the model's 'scheme', 'host', 'path', and "
					. "'properties' properties to be set"
			);	
		}
		
		$this->client = $client;
		
		return;
	}
	
	/**
	 * Called when the model is destructed
	 *
	 * I make sure the API client is destructed, and in turn, that any request and
	 * connection resources are cleaned up.
	 *
	 * @return  void
	 * @since  0.1.0
	 */ 
	public function __destruct()
	{
		unset($this->client);
		
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
		if ( ! array_key_exists($name, $this->data)) {
			throw new \OutOfBoundsException("Property $name does not exist");
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
		return isset($this->data[$name]);
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
	 * @since  0.1.0
	 */
	public function __set($name, $value)
	{
		$this->data[$name] = $value;
		
		return;
	}
	
	/**
	 * Called when unset() is used on inaccessible property
	 *
	 * @return  void
	 * @since  0.1.0
	 */
	public function __unset($name)
	{
		unset($this->data[$name]);
		
		return;
	}
	
	
	/* !Public methods */
	
	/**
	 * Create a new model
	 *
	 * I'll set the model's id property if successful.
	 *
	 * @return  self
	 * @throws  BadMethodCallException  if the model has an id
	 * @throws  BadMethodCallException  if the request is not a create request
	 * @throws  BadMethodCallException  if the response is not a create response
	 * @throws  RuntimeException        if the api call fails
	 * @since  0.1.0
	 */
	public function create()
	{
		// if the model has been read, short-circuit
		if (isset($this->id)) {
			throw new \BadMethodCallException(
				"You can't call create() on a model that has been read"
			);
		}
		
		// if the client's request is not a create request, short-circuit
		if ( ! $this->client->getRequest() instanceof Api\Request\Create) {
			throw new \BadMethodCallException(
				"You can't call create() without a create request"
			);
		}
		
		// if the client's response is not a create response, short-circuit
		if ( ! $this->client->getResponse() instanceof Api\Response\Create) {
			throw new \BadMethodCallException(
				"You can't call create() without a create response"
			);
		}
		
		// otherwise, set the request's data
		$request = $this->client->getRequest()->setData($this->data);
		
		// execute the api call
		$this->client->execute();
		
		// set the model's id
		$this->id = $this->client->getResponse()->getId();
		
		return $this;
	}
	
	/**
	 * Deletes an existing model
	 *
	 * On success, I'll set the model's id to null.
	 *
	 * @return  self
	 * @throws  BadMethodCallException  if the model does not have an id
	 * @throws  BadMethodCallException  if the request is not a delete request
	 * @throws  BadMethodCallException  if the response is not a delete response
	 * @throws  RuntimeException        if the api call fails
	 * @since  0.1.0
	 */
	public function delete()
	{
		// if the model has not been read, short-circuit
		if ( ! isset($this->id)) {
			throw new \BadMethodCallException(
				"You can't call delete() on a model that has not been read"
			);
		}
		
		// if the client's request is not a delete request, short-circuit
		if ( ! $this->client->getRequest() instanceof Api\Request\Delete) {
			throw new \BadMethodCallException(
				"You can't call delete() without a delete request"
			);
		}
		
		// if the client's response is not a delete response, short-circuit
		if ( ! $this->client->getResponse() instanceof Api\Response\Delete) {
			throw new \BadMethodCallException(
				"You can't call delete() without a delete response"
			);
		}
		
		// execute the api call
		$this->client->execute();
		
		// clear the model's id
		unset($this->id);
		
		return $this;
	}
	
	/**
	 * Hydrates the model's data
	 *
	 * Obviously, this introduces security issues if a model has properties a user
	 * should not access. However, for the purposes of this test, I think this will
	 * work.
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
	
	/**
	 * Indexes the models
	 *
	 * @return  Jstewmc\Model\Model[]
	 * @throws  BadMethodCallException  if the request is not an index request
	 * @throws  BadMethodCallException  if the response is not an index response
	 * @throws  RuntimeException        if the api call fails
	 * @since  0.1.0
	 */
	public function index()
	{
		// if the client's request is not an index request, short-circuit
		if ( ! $this->client->getRequest() instanceof Api\Request\Index) {
			throw new \BadMethodCallException(
				"You can't call index() without an index request"
			);
		}
		
		// if the client's response is not an index response, short-circuit
		if ( ! $this->client->getResponse() instanceof Api\Response\Index) {
			throw new \BadMethodCallException(
				"You can't call index() without an index response"
			);
		}
		
		$models = [];
		
		// execute the api call
		$this->client->execute();
		
		// get the called class' classname
		$classname = get_class($this);
		
		// loop through the response's entities
		foreach ($this->client->getResponse()->getEntities() as $data) {
			// create and append a new model
			$models[] = (new $classname($this->client))->hydrate($data); 
		}
		
		return $models;
	}
	
	/**
	 * Reads a model
	 *
	 * @param  int  the model's id
	 * @return  self
	 * @throws  BadMethodCallException  if the model's id is not set
	 * @throws  BadMethodCallException  if the request is not a read request
	 * @throws  BadMethodCallException  if the response is not a read response
	 * @throws  RuntimeException        if the api call fails
	 * @since  0.1.0
	 */
	public function read()
	{
		// if the model doesn't have an id, short-circuit
		if ( ! isset($this->id)) {
			throw new \BadMethodCallException(
				"You can't call read() on a model that doesn't have an id"
			);
		}
		
		// if the client's request is not a read request, short-circuit
		if ( ! $this->client->getRequest() instanceof Api\Request\Read) {
			throw new \BadMethodCallException(
				"You can't call read() without a read request"
			);
		}
		
		// if the client's response is not a read response, short-circuit
		if ( ! $this->client->getResponse() instanceof Api\Response\Read) {
			throw new \BadMethodCallException(
				"You can't call read() without a read response"
			);
		}
		
		// set the model's id
		$this->id = $id;
		
		// execute the api call
		$this->client->execute();
		
		// set the model's data
		$this->hydrate($response->getData());
		
		return $this;
	}
	
	/**
	 * Updates the model's data
	 *
	 * @return  self
	 * @throws  BadMethodCallException  if the model does not have an id
	 * @throws  BadMethodCallException  if the request is not an update request
	 * @throws  BadMethodCallException  if the response is not an update response
	 * @throws  RuntimeException        if the api call fails
	 * @since  0.1.0
	 */
	public function update()
	{
		// if the model hasn't been read, short-circuit
		if ( ! isset($this->id)) {
			throw new \BadMethodCallException(
				"You can't call update() on a model that hasn't been read"
			);
		}
		
		// if the client's request is not an update request, short-circuit
		if ( ! $this->client->getRequest() instanceof Api\Request\Update) {
			throw new \BadMethodCallException(
				"You can't call update() without an update request"
			);
		}
		
		// if the client's response is not an update response, short-circuit
		if ( ! $this->client->getResponse() instanceof Api\Response\Update) {
			throw new \BadMethodCallException(
				"You can't call update() without an update response"
			);
		}
		
		// set the request's data
		$this->client->getRequest()->setData($this->data);
		
		// execute the api call
		$this->client->execute();
		
		return $this;
	}
	
	/**
	 * Returns the model's url
	 *
	 * @param  bool  $id  a flag indicating whether or not the url should include
	 *     the model's id (optional; if omitted, defaults to false)
	 * @return  Jstewmc\Url\Url
	 */
	public function url($id = false)
	{
		$url = (new \Jstewmc\Url\Url())
			->setScheme(static::$scheme)
			->setHost(static::$host)
			->setPath(static::$path);
		
		if ($isKey) {
			// hmmm, apparently my Path library is a little strict on types
			$url->getPath()->appendSegment((string) $this->id);
		}
		
		return $url;
	}
}
