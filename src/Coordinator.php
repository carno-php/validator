<?php
/**
 * Valid resources coordinator
 * User: moyo
 * Date: 2018/6/4
 * Time: 2:25 PM
 */

namespace Carno\Validator;

use Carno\Validator\Chips\VWaits;
use Carno\Validator\Exception\IncompleteCoordinationException;
use Carno\Validator\Valid\Cloning;
use Carno\Validator\Valid\Group;
use Carno\Validator\Valid\Inherit;
use Carno\Validator\Valid\Named;

class Coordinator
{
    /**
     * @var Group
     */
    private $group = null;

    /**
     * @var Inherit
     */
    private $inherit = null;

    /**
     * @var Named
     */
    private $named = null;

    /**
     * @var Cloning
     */
    private $clone = null;

    /**
     * Coordinator constructor.
     * @param Group $group
     * @param Inherit $inherit
     * @param Named $named
     * @param Cloning $clone
     */
    public function __construct(
        Group $group,
        Inherit $inherit,
        Named $named,
        Cloning $clone
    ) {
        $this->group = $group;
        $this->inherit = $inherit;
        $this->named = $named;
        $this->clone = $clone;
    }

    /**
     * @return Group
     */
    public function group() : Group
    {
        return $this->group;
    }

    /**
     * @return Inherit
     */
    public function inherit() : Inherit
    {
        return $this->inherit;
    }

    /**
     * @return Named
     */
    public function named() : Named
    {
        return $this->named;
    }

    /**
     * @return Cloning
     */
    public function clone() : Cloning
    {
        return $this->clone;
    }

    /**
     */
    public function checking() : void
    {
        $waits = [
            'v-named' => $this->named(),
            'v-group' => $this->group(),
        ];

        /**
         * @var VWaits $wait
         */

        foreach ($waits as $type => $wait) {
            if (!$wait->resolved()) {
                throw new IncompleteCoordinationException(
                    sprintf('%s -> %s', $type, implode(',', $wait->incomplete()))
                );
            }
        }
    }
}
