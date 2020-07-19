<?php

namespace Lar\Roads;

/**
 * Class RedirectController
 *
 * @package Lar\Roads
 */
class ComponentController {

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
        }

        else if (class_exists("\\App\\Components\\{$method}")) {

            return new ("\\App\\Components\\{$method}")(...$parameters);
        }

        throw new \Exception("Component [{$method}] not found!");
    }
}
