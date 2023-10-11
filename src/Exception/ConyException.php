<?php
namespace carry0987\Cony\Exception;

use InvalidArgumentException;

class ConyException extends InvalidArgumentException
{
    const E_TRANSFORM_TYPE = 'Unknown transform type. Allowed types are: '
        . 'Cony::TRANSFORM_NONE, Cony::TRANSFORM_UPPERCASE, Cony::TRANSFORM_LOWERCASE';
}
