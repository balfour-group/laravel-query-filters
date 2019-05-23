<?php

namespace Balfour\LaravelQueryFilters;

abstract class Utils
{
    /**
     * @param string $value
     * @param string $char
     * @return string
     */
    public static function escapeLike($value, $char = '\\')
    {
        // see https://stackoverflow.com/questions/22749182/laravel-escape-like-clause/42028380#42028380
        return str_replace(
            [$char, '%', '_'],
            [$char.$char, $char.'%', $char.'_'],
            $value
        );
    }
}
