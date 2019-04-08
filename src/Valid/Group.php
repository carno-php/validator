<?php
/**
 * Valid group
 * User: moyo
 * Date: 2018/6/4
 * Time: 2:17 PM
 */

namespace Carno\Validator\Valid;

use Carno\Validator\Chips\VWaits;
use Carno\Validator\Exception\DuplicatedGroupException;
use Closure;

class Group
{
    use VWaits;

    /**
     * @var string
     */
    private $session = null;

    /**
     * @var array
     */
    private $groups = [];

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name) : bool
    {
        return isset($this->groups[$name]);
    }

    /**
     * @param string $name
     * @param Closure $walker
     */
    public function traversing(string $name, Closure $walker) : void
    {
        if ($group = $this->groups[$name] ?? null) {
            foreach ($group['fields'] as $field) {
                $walker($group['linked'], $field);
            }
        }
    }

    /**
     * @param string $named
     * @param Executor $linker
     */
    public function start(string $named, Executor $linker) : void
    {
        if (isset($this->groups[$named])) {
            throw new DuplicatedGroupException($named);
        }
        $this->session = $named;
        $this->groups[$named] = ['linked' => $linker];
    }

    /**
     * @param Rule $rule
     */
    public function attach(Rule $rule) : void
    {
        $this->session && ($this->groups[$this->session]['fields'][] = $rule->f());
    }

    /**
     */
    public function stop() : void
    {
        if ($this->session) {
            $this->waiting($this->session) && $this->resolving($this->session);
            $this->session = null;
        }
    }
}
