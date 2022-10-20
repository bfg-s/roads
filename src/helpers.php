<?php

if (! function_exists('last_namespace_element')) {

    /**
     * Get last namespace part by level.
     *
     * @param string $namespace
     * @param int $level
     * @return string
     */
    function last_namespace_element(string $namespace, int $level = 1) : string
    {
        return Bfg\Entity\Core\Entities\NamespaceEntity::lastSegment($namespace, $level);
    }
}

if (! function_exists('body_namespace_element')) {

    /**
     * Get only namespace body.
     *
     * @param string $namespace
     * @param int $level
     * @param string $delimiter
     * @return string
     */
    function body_namespace_element(string $namespace, int $level = 1, string $delimiter = '\\') : string
    {
        return Bfg\Entity\Core\Entities\NamespaceEntity::bodySegment($namespace, $level);
    }
}
