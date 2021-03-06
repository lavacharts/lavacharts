<?php

namespace Khill\Lavacharts\Javascript;

use Khill\Lavacharts\Exceptions\InvalidElementIdException;
use Khill\Lavacharts\Support\Buffer;
use Khill\Lavacharts\Support\Options;
use Khill\Lavacharts\Support\Renderable;
use Khill\Lavacharts\Support\Contracts\Customizable;
use Khill\Lavacharts\Support\Traits\HasOptionsTrait as HasOptions;

/**
 * ScriptManager Class
 *
 * This class takes charts and uses all the info to build the complete
 * javascript blocks for outputting into the page. Also will output the lava.js module
 * and track if it is in page or not.
 *
 * @category   Class
 * @package    Khill\Lavacharts\Javascript
 * @since      3.0.5
 * @author     Kevin Hill <kevinkhill@gmail.com>
 * @copyright  (c) 2017, KHill Designs
 * @link       http://github.com/kevinkhill/lavacharts GitHub Repository Page
 * @link       http://lavacharts.com                   Official Docs Site
 * @license    http://opensource.org/licenses/MIT      MIT
 */
class ScriptManager implements Customizable
{
    use HasOptions;

    /**
     * Lava.js module location.
     *
     * @var string
     */
    const LAVA_JS = '/../../javascript/dist/lava.js';

    /**
     * Opening javascript tag.
     *
     * @var string
     */
    const JS_OPEN = '<script type="text/javascript">';

    /**
     * Closing javascript tag.
     *
     * @var string
     */
    const JS_CLOSE = '</script>';

    /**
     * Script output buffer.
     *
     * @var Buffer
     */
    private $outputBuffer;

    /**
     * Tracks if the lava.js module and google loader have been output.
     *
     * @var bool
     */
    private $lavaJsLoaded = false;

    /**
     * Status of whether the scripts have been output to the page.
     *
     * @var bool
     */
    private $scriptsOutput = false;


    /**
     * Wraps a buffer with an html script tag
     *
     * @param Buffer $buffer
     * @return Buffer
     */
    public static function scriptTagWrap(Buffer $buffer)
    {
        return $buffer
//            ->prepend(PHP_EOL)
            ->prepend(static::JS_OPEN)
//            ->prepend(PHP_EOL)
//            ->append(PHP_EOL)
            ->append(static::JS_CLOSE);
    }

    /**
     * ScriptManager constructor.
     *
     * @param array $options
     */
    function __construct(/*$options = []*/)
    {
        $this->outputBuffer = new Buffer();

//        $this->setOptions($options);
    }

    /**
     * Returns true|false depending on if the lava.js module
     * and renderables have been output to the page.
     *
     * @return bool
     */
    public function scriptsOutput()
    {
        return $this->scriptsOutput;
    }

    /**
     * Returns true|false depending on if the lava.js module
     * has be output to the page
     *
     * @return bool
     */
    public function lavaJsLoaded()
    {
        return $this->lavaJsLoaded;
    }

    /**
     * Appends an opening script tag to the output buffer.
     *
     * @since 4.0.0
     * @return self
     */
    public function openScriptTag()
    {
        $this->outputBuffer->append(static::JS_OPEN);

        return $this;
    }

    /**
     * Appends a closing script tag to the output buffer.
     *
     * @since 4.0.0
     * @return self
     */
    public function closeScriptTag()
    {
        $this->outputBuffer->append(static::JS_CLOSE);

        return $this;
    }

    /**
     * Returns the output buffer of the ScriptManager.
     *
     * @since 4.0.0
     * @return \Khill\Lavacharts\Support\Buffer
     */
    public function getOutputBuffer()
    {
        return $this->outputBuffer;
    }

    /**
     * Initialize the output buffer with the Lava.js module.
     *
     * @since 4.0.0
     * @param $options
     */
    public function loadLavaJs($options)
    {
        $this->outputBuffer = $this->getLavaJs($options);
    }

    /**
     * Gets the lava.js module.
     *
     * @param array $options
     * @return \Khill\Lavacharts\Support\Buffer
     */
    public function getLavaJs($options)
    {
        $this->lavaJsLoaded = true;

        $buffer = new ScriptBuffer($this->getLavaJsSource());

        $options = Options::create($options);

        $buffer->pregReplace('/OPTIONS_JSON/', $options->toJson());

        return $buffer;
    }

    /**
     * Add a renderable to the output buffer.
     *
     * @since 4.0.0
     * @param \Khill\Lavacharts\Support\Renderable $renderable
     * @throws \Khill\Lavacharts\Exceptions\InvalidElementIdException
     */
    public function addRenderableToOutput(Renderable $renderable)
    {
        if (! $renderable->hasElementId()) {
            throw new InvalidElementIdException($renderable);
        }

        $buffer = new Buffer($renderable);

        /** Converting string dates to date constructors */
        $buffer->pregReplace('/"Date\(((:?[0-9]+,?)+)\)"/', 'new Date(\1)');

        /** Converting string nulls to actual nulls */
        $buffer->pregReplace('/"null"/', 'NULL');

        $this->outputBuffer->append($buffer);
    }

    /**
     * Get the source of the lava.js module as a Buffer
     *
     * @return string
     */
    private function getLavaJsSource()
    {
        $lavaJs = realpath(__DIR__ . self::LAVA_JS);

        return file_get_contents($lavaJs);
    }
}
