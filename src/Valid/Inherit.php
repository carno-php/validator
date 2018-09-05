<?php
/**
 * Valid inherit
 * User: moyo
 * Date: 2018/6/4
 * Time: 2:22 PM
 */

namespace Carno\Validator\Valid;

class Inherit
{
    /**
     * @param string $group
     * @param Group $source
     * @param Executor $linker
     */
    public function sync(string $group, Group $source, Executor $linker) : void
    {
        $fn = static function () use ($group, $source, $linker) {
            $source->traversing($group, function (Executor $origin, string $field) use ($linker) {
                $linker->import($origin->export($field));
            });
        };

        $source->has($group) ? $fn() : $source->wait($group, $fn);
    }
}
