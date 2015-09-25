<?php
class ControllerException extends Exception
{
    public function __toString()
    {
        return $this->getMessage();
    }
}
?>
