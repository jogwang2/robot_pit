<?php

namespace App\Models;

abstract class BaseManager
{
	public $success;
	public $code;
	public $message;
	public $data;


	/**
     * Sets the class response
     *
     */
	protected function setResponse($success, $message, $data, $code = "")
	{
		$this->success = $success;
        $this->message = $message;
        $this->data = $data;
        $this->code = $code;
	}
}