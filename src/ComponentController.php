<?php

namespace Lar\Roads;

/**
 * Class RedirectController.
 *
 * @package Lar\Roads
 */
class ComponentController
{
    /**
     * @param $method
     * @param $parameters
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $parameters)
    {
        if (class_exists($method)) {
            return new $method(...$parameters);
        } elseif (class_exists("\\App\\Components\\{$method}")) {
            $c = "\\App\\Components\\{$method}";

            return new $c(...$parameters);
        }

        throw new \Exception("Component [{$method}] not found!");
    }
}
