<?php
/**
 * Sourcing API
 * User: moyo
 * Date: 2018/6/4
 * Time: 6:00 PM
 */

namespace Carno\Validator\Contracts;

use Carno\Validator\Valid\Executor;
use Throwable;

interface Sourcing
{
    /**
     * @param Executor $executor
     * @param mixed $input
     * @throws Throwable
     */
    public function validating(Executor $executor, $input);
}
