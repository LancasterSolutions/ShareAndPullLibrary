<?php

namespace ShapLibrary;

/**
 * Class ShapLibraryException
 *
 * @package ShapLibrary
 */
class ShapLibraryException extends \Exception
{
    public function __construct(\Exception $exception)
    {
        parent::__construct($exception->getMessage(), $exception->getCode(), $exception);
    }

    public function getDecodedMessage()
    {
        return json_decode($this->getMessage(), true);
    }

    public function getDecodedHttpStatus()
    {
        return json_decode($this->getCode(), true);
    }
}
