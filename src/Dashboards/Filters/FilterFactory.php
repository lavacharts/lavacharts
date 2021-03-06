<?php

namespace Khill\Lavacharts\Dashboards\Filters;

use Khill\Lavacharts\Exceptions\InvalidConfigValue;
use Khill\Lavacharts\Exceptions\InvalidFilter;
use Khill\Lavacharts\Exceptions\InvalidFilterType;
use Khill\Lavacharts\Exceptions\InvalidParamType;
use Khill\Lavacharts\Support\Args;
use Khill\Lavacharts\Support\Arr;

/**
 * FilterFactory creates new filters for use in a dashboard.
 *
 *
 * @package   Khill\Lavacharts\Dashboards\Filters
 * @since     3.1.0
 * @author    Kevin Hill <kevinkhill@gmail.com>
 * @copyright (c) 2017, KHill Designs
 * @link      http://github.com/kevinkhill/lavacharts GitHub Repository Page
 * @link      http://lavacharts.com                   Official Docs Site
 * @license   http://opensource.org/licenses/MIT MIT
 */
class FilterFactory
{
    /**
     * Valid filter types
     */
    const TYPES = [
        'Category',
        'ChartRange',
        'DateRange',
        'NumberRange',
        'String'
    ];

    /**
     * Create a new Filter.
     *
     * @param  string $type
     * @param  array  $args
     * @return Filter
     * @throws \Khill\Lavacharts\Exceptions\InvalidFilterType
     */
    public static function create($type, $args)
    {
        $args = new Args($args);

        $labelOrIndex = $args->verify(0, ['string', 'int']);
        $options = $args->verify(1, 'array', []);

        // Strip 'Filter' if given
        if (is_string($type)) {
            $type = str_replace('Filter', '', $type);
        }

        // Check if valid filter type
        if (in_array($type, self::TYPES, true) === false) {
            throw new InvalidFilterType($type);
        }

        // Build the namespace
        $filter = self::makeNamespace($type);

        return new $filter($labelOrIndex, $options);
    }

    /**
     * Build the namespace to create a new filter.
     *
     * @param  string $filter
     * @return string
     */
    private static function makeNamespace($filter)
    {
        if (strpos($filter, 'range') !== false) {
            $filter = ucfirst(str_replace('range', 'Range', $filter));
        }

        return __NAMESPACE__ . '\\' . $filter . 'Filter';
    }
}
