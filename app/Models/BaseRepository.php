<?php

namespace App\Models;

abstract class BaseRepository
{
	public $success;
	public $code;
	public $message;
	public $data;


	/**
     * Sets the class response
     *
     * @param boolean $success
     * @param string  $message
     * @param array   $data
     * @param int     $code
     */
	protected function setResponse($success, $message, $data, $code = "")
	{
		$this->success = $success;
        $this->message = $message;
        $this->data = $data;
        $this->code = $code;
	}

	/**
     * Sets the class response
     *
     * @param boolean $success
     * @param string  $message
     * @param int     $code
     */
	protected function setResponseNoData($success, $message, $code = "")
	{
		$this->success = $success;
        $this->message = $message;
        $this->code = $code;
	}

	/**
     * Sets the class response
     *
     * @param array   $data
     */
	protected function addResponseData($data)
	{
		$this->data[] = $data;
	}
}