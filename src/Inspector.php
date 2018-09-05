<?php
/**
 * Validation inspector
 * User: moyo
 * Date: 2018/6/4
 * Time: 6:03 PM
 */

namespace Carno\Validator;

use Carno\Validator\Contracts\Sourcing;
use Carno\Validator\Valid\Executor;
use Throwable;

class Inspector
{
    /**
     * @var Executor[][]
     */
    private $executors = [];

    /**
     * @var Sourcing
     */
    private $sourcing = null;

    /**
     * Inspector constructor.
     * @param Sourcing $sourcing
     */
    public function __construct(Sourcing $sourcing)
    {
        $this->sourcing = $sourcing;
    }

    /**
     * @param string $class
     * @param string $method
     * @param Executor $executor
     */
    public function join(string $class, string $method, Executor $executor) : void
    {
        $this->executors[$class][$method] = $executor;
    }

    /**
     * @param string $class
     * @param string $method
     * @param mixed $input
     * @throws Throwable
     */
    public function valid(string $class, string $method, $input)
    {
        if ($executor = $this->executors[$class][$method] ?? null) {
            $this->sourcing->validating($executor, $input);
        }
    }
}
