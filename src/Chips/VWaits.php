<?php
/**
 * Valid waits for [group|named]
 * User: moyo
 * Date: 2018/6/5
 * Time: 2:13 PM
 */

namespace Carno\Validator\Chips;

use Closure;

trait VWaits
{
    /**
     * @var array
     */
    private $waits = [];

    /**
     * @param string $key
     * @param Closure $fn
     */
    public function wait(string $key, Closure $fn) : void
    {
        $this->waits[$key][] = $fn;
    }

    /**
     * @return bool
     */
    public function resolved() : bool
    {
        return empty($this->waits);
    }

    /**
     * @return array
     */
    public function incomplete() : array
    {
        return array_keys($this->waits);
    }

    /**
     * @param string $key
     * @return bool
     */
    private function waiting(string $key) : bool
    {
        return isset($this->waits[$key]);
    }

    /**
     * @param string $key
     * @param mixed ...$params
     */
    private function resolving(string $key, ...$params) : void
    {
        foreach ($this->waits[$key] ?? [] as $fn) {
            $fn(...$params);
        }
        unset($this->waits[$key]);
    }
}
