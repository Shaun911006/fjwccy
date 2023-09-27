<?php
/**
 * Author:Shaun·Yang
 * Date:2023/9/26
 * Time:上午10:30
 * Description:
 */

namespace fjwccy\exception;

use Throwable;

class ValidateException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}