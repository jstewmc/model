<?php

namespace Jstewmc\Model\Api;

/**
 * The model's API client interface
 *
 * @author     Jack Clayton
 * @copyright  2015 Jack Clayton
 * @license    MIT
 * @since      0.1.0
 */
interface Client 
{
	/* !Public methods */
	
	/**
	 * Gets the client's request
	 *
	 * @return  Request\Request  the client's request
	 * @since  0.1.0
	 */
	public function getRequest();
	
	/**
	 * Gets the client's response
	 *
	 * @return  Response\Response  the client's response
	 * @since  0.1.0
	 */
	public function getResponse();
	
	/**
	 * Executes the request
	 *
	 * @return  void
	 * @since  0.1.0
	 */
	public function execute();
	
	/**
	 * Sets the client's request
	 *
	 * @param  Request\Request  $request  the client's request
	 * @return  self
	 * @since  0.1.0
	 */
	public function setRequest(Request\Request $request);
	
	/**
	 * Sets the client's response
	 *
	 * @param  Response\Response  $response  the client's response
	 * @return  self
	 * @since  0.1.0
	 */
	public function setResponse(Response\Response $response);
}
