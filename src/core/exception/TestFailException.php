<?php
namespace Everest\core\exception;

class TestFailException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}

?>