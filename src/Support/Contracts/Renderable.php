<?php

namespace Khill\Lavacharts\Support\Contracts;
//TODO: redundant??
/**
 * Renderable Interface
 *
 * Defining the methods a class must have to be Renderable.
 *
 * @package    Khill\Lavacharts\Support
 * @since      3.1.0
 * @author     Kevin Hill <kevinkhill@gmail.com>
 * @copyright  (c) 2017, KHill Designs
 * @link       http://github.com/kevinkhill/lavacharts GitHub Repository Page
 * @link       http://lavacharts.com                   Official Docs Site
 * @license    http://opensource.org/licenses/MIT MIT
 */
interface Renderable
{
    /**
     * Returns the ElementId.
     *
     * @return string
     */
    public function getElementId();

    /**
     * Returns the label.
     *
     * @return string
     */
    public function getLabel();

    /**
     * Returns if the Renderable object is able to be rendered.
     *
     * @return bool
     */
    public function isRenderable();
}
