<?php

namespace Khill\Lavacharts\Charts;

use Khill\Lavacharts\Support\Buffer;
use Khill\Lavacharts\Support\Contracts\Customizable;
use Khill\Lavacharts\Support\Contracts\DataInterface;
use Khill\Lavacharts\Support\Contracts\Javascriptable;
use Khill\Lavacharts\Support\Contracts\Visualization;
use Khill\Lavacharts\Support\Contracts\Wrappable;
use Khill\Lavacharts\Support\Renderable;
use Khill\Lavacharts\Support\StringValue as Str;
use Khill\Lavacharts\Support\Traits\HasDataTableTrait as HasDataTable;
use Khill\Lavacharts\Support\Traits\ToJavascriptTrait as ToJavascript;

/**
 * Class Chart
 *
 * Parent to all charts which has common properties and methods
 * used between all the different charts.
 *
 *
 * @package       Khill\Lavacharts\Charts
 * @author        Kevin Hill <kevinkhill@gmail.com>
 * @copyright (c) 2017, KHill Designs
 * @link          http://github.com/kevinkhill/lavacharts GitHub Repository Page
 * @link          http://lavacharts.com                   Official Docs Site
 * @license       http://opensource.org/licenses/MIT      MIT
 */
class Chart extends Renderable implements Customizable, Javascriptable, Visualization, Wrappable
{
    use HasDataTable, ToJavascript;

    /**
     * Type of wrappable class
     */
    const WRAP_TYPE = 'chartType';

    /**
     * Builds a new chart with the given label.
     *
     * @param string        $label   Identifying label for the chart.
     * @param DataInterface $data    DataTable used for the chart.
     * @param array         $options Options for the chart.
     */
    public function __construct($label, DataInterface $data = null, array $options = [])
    {
        $this->label     = Str::verify($label);
        $this->datatable = $data;

        $this->setOptions($options);

        if ($this->options->hasAndIs('elementId', 'string')) {
            $this->elementId = $this->options->elementId;
        }
    }

    /**
     * Returns the Filter wrap type.
     *
     * @since  3.0.5
     * @return string
     */
    public function getWrapType()
    {
        return static::WRAP_TYPE;
    }

    /**
     * Returns the chart version.
     *
     * So far, all the charts but Calendar are version 1
     *
     * @since  3.0.5
     * @return string
     */
    public function getVersion()
    {
        return '1';
    }

    /**
     * Returns the chart visualization class.
     *
     * Most charts are part of the "corechart" package.
     *
     * @since  3.0.5
     * @return string
     */
    public function getJsPackage()
    {
        return 'corechart';
    }

    /**
     * Returns the javascript visualization package for instantiation.
     *
     * The chart type it is automatically prepended with "google.visualization." which
     * is the default for most charts.
     *
     * @since  3.0.5
     * @return string
     */
    public function getJsClass()
    {
        return static::GOOGLE_VISUALIZATION . $this->getType();
    }

    /**
     * Retrieves the formats from the datatable that is defined on the chart.
     *
     *
     * The formats will be serialized down to javascript source and added
     * to a string buffer.
     *
     * If no formats are defined, then an empty buffer will be returned.
     *
     * @since  4.0.0
     * @return Buffer The contents will be javascript source
     */
    public function getFormats()
    {
//        $buffer = new Buffer();
//
//        if (! method_exists($this->datatable, 'getFormattedColumns')) {
//            $buffer->append('console.log("');
//            $buffer->append('[lavacharts] The implementation of DataInterface did not have ');
//            $buffer->append('getFormattedColumns() defined, so the data was not formatted.');
//            $buffer->append('");');
//
//            return $buffer;
//        }

        $formats = [];

        /**
         * @var \Khill\Lavacharts\DataTables\Columns\Column $column
         */
        foreach ($this->datatable->getFormattedColumns() as $column) {
//            $buffer->append(
                $formats[] = $column->getFormat()->toArray();//->toJson()
//            );
        }

        return $formats;
//        return $buffer;
    }

    /**
     * Convert the Chart to Javascript.
     *
     * @return string
     */
    public function toJavascript()
    {
        return sprintf($this->getJavascriptFormat(), $this->getJavascriptSource());
    }

    /**
     * Return the JSON payload that will be passed to lava.createChart.
     *
     * @return string
     */
    public function getJavascriptSource()
    {
        return $this->toJson();
    }

    /**
     * Return a format string that will be used to convert the class to javascript.
     *
     * @lang javascript
     * @return string
     */
    public function getJavascriptFormat()
    {
        return 'window.lava.addNewChart(%s);';
    }

    /**
     * Array representation of the Chart.
     *
     * @return array
     */
    public function toArray()
    {
        $chartArray = [
            'pngOutput' => false,
            'label'     => $this->getLabel(),
            'type'      => $this->getType(), // TODO: unused in js side?
            'class'     => $this->getJsClass(),
            'elementId' => $this->getElementId(),
            'formats'   => $this->getFormats(),
            'datatable' => $this->getDataTable(),
            'packages'  => [$this->getJsPackage()],
            'options'   => $this->options->without(['events']),
            'events'    => $this->hasOption('events') ? $this->options->events : [],
        ];

//        if (method_exists($this->datatable, 'toJsDataTable')) {
//            $chartArray['datatable'] = $this->datatable;//toJsDataTable();
//        }

        if (method_exists($this, 'getPngOutput')) {
            $chartArray['pngOutput'] = $this->getPngOutput();
        }

        if (method_exists($this, 'getMaterialOutput') &&
            $this->getMaterialOutput()
        ) {
            $chartArray['options'] = sprintf(
                $this->getJsClass() . '.convertOptions(%s)',
                $this->getOptions()->toJson()
            );
        }

        return $chartArray;
    }
}
