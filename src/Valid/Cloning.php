<?php
/**
 * Valid clone
 * User: moyo
 * Date: 2018/6/4
 * Time: 3:26 PM
 */

namespace Carno\Validator\Valid;

class Cloning
{
    /**
     * @param string $expr
     * @param Executor $to
     * @param Named $repo
     */
    public function from(string $expr, Executor $to, Named $repo) : void
    {
        $named = trim(substr($expr, 0, $fwp = strpos($expr, ' ')));
        $expr = trim(substr($expr, $fwp + 1));

        // named field [expr]
        if ($swp = strpos($expr, ' ')) {
            $field = trim(substr($expr, 0, $swp));
        } else {
            $field = $expr;
            $expr = '';
        }

        $fn = function (Rule $rule) use ($to, $field, $expr) {
            $to->import(new Rule($field, $rule->v(), $rule->e()));
            $expr && $to->analyzing($expr);
        };

        $repo->has($named) ? $fn($repo->get($named)) : $repo->wait($named, $fn);
    }
}
