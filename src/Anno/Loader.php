<?php
/**
 * Annotations loader
 * User: moyo
 * Date: 2018/6/4
 * Time: 10:45 AM
 */

namespace Carno\Validator\Anno;

use Carno\Container\Injection\Annotation;
use Carno\Validator\Coordinator;
use Carno\Validator\Inspector;
use Carno\Validator\Valid\Executor;
use ReflectionClass;
use ReflectionMethod;

class Loader
{
    /**
     * @var Coordinator
     */
    private $coordinator = null;

    /**
     * @var Inspector
     */
    private $inspector = null;

    /**
     * @var Executor
     */
    private $builder = null;

    /**
     * Loader constructor.
     * @param Coordinator $coordinator
     * @param Inspector $inspector
     */
    public function __construct(Coordinator $coordinator, Inspector $inspector)
    {
        $this->coordinator = $coordinator;
        $this->inspector = $inspector;
        $this->builder = new Executor;
    }

    /**
     * @param string $class
     */
    public function parsing(string $class) : void
    {
        foreach ((new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $builder = new Executor;

            (new Annotation($method->getDocComment()))->rowing(function (string $key, $expr) use ($builder) {
                $this->acknowledge($builder, $key, $expr);
            });

            $this->coordinator->group()->stop();

            $this->inspector->join($class, $method->getName(), $builder);
        }
    }

    /**
     * @param array $rules
     */
    public function walking(array $rules) : void
    {
        foreach ($rules as $rule) {
            $this->acknowledge($this->builder, array_shift($rule), implode(' ', $rule) ?: true);
        }
    }

    /**
     * @param Executor $builder
     * @param string $ak
     * @param string $av
     */
    private function acknowledge(Executor $builder, string $ak, $av) : void
    {
        switch ($ak) {
            case 'valid-group':
                $av === true
                    ? $this->coordinator->group()->stop()
                    : $this->coordinator->group()->start($av, $builder);
                break;
            case 'valid-inherit':
                $this->coordinator->inherit()->sync($av, $this->coordinator->group(), $builder);
                break;
            case 'valid-named':
                $this->coordinator->group()->attach($this->coordinator->named()->mark($av, $builder));
                break;
            case 'valid-clone':
                $this->coordinator->clone()->from($av, $builder, $this->coordinator->named());
                break;
            case 'valid':
                $this->coordinator->group()->attach($builder->analyzing($av));
        }
    }
}
