<?php
/**
 * Valid named
 * User: moyo
 * Date: 2018/6/4
 * Time: 3:25 PM
 */

namespace Carno\Validator\Valid;

use Carno\Validator\Chips\VWaits;

class Named
{
    use VWaits;

    /**
     * @var Rule[]
     */
    private $alias = [];

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name) : bool
    {
        return isset($this->alias[$name]);
    }

    /**
     * @param string $name
     * @return Rule
     */
    public function get(string $name) : Rule
    {
        return $this->alias[$name];
    }

    /**
     * @param string $expr
     * @param Executor $valid
     * @return Rule
     */
    public function mark(string $expr, Executor $valid) : Rule
    {
        $named = trim(substr($expr, 0, $fwp = strpos($expr, ' ')));
        $expr = trim(substr($expr, $fwp + 1));

        $this->alias[$named] = $rule = $valid->analyzing($expr);

        $this->waiting($named) && $this->resolving($named, $rule);

        return $rule;
    }
}
