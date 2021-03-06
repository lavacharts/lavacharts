/**
 * Dashboard module
 *
 * @class     Dashboard
 * @module    lava/Dashboard
 * @author    Kevin Hill <kevinkhill@gmail.com>
 * @copyright (c) 2017, KHill Designs
 * @license   MIT
 */
import { Renderable } from './Renderable.es6';
import { stringToFunction } from './Utils.es6';

/**
 * Dashboard class
 *
 * @typedef {Function}  Chart
 * @property {string}   label     - Label for the chart.
 * @property {string}   type      - Type of chart.
 * @property {Object}   element   - Html element in which to render the chart.
 * @property {Object}   chart     - Google chart object.
 * @property {string}   package   - Type of Google chart package to load.
 * @property {boolean}  pngOutput - Should the chart be displayed as a PNG.
 * @property {Object}   data      - Datatable for the chart.
 * @property {Object}   options   - Configuration options for the chart.
 * @property {Array}    formats   - Formatters to apply to the chart data.
 * @property {Object}   promises  - Promises used in the rendering chain.
 * @property {Function} init      - Initializes the chart.
 * @property {Function} configure - Configures the chart.
 * @property {Function} render    - Renders the chart.
 * @property {Function} uuid      - Creates identification string for the chart.
 * @property {Object}   _errors   - Collection of errors to be thrown.
 */
export class Dashboard extends Renderable
{
    constructor(json) {
        super(json);

        this.type     = 'Dashboard';
        this.bindings = json.bindings;

        /**
         * Any dependency on window.google must be in the render scope.
         */
        this.render = () => {
            this.setData(json.datatable);

            this.gchart = new google.visualization.Dashboard(this.element);

            this._attachBindings();

            if (this.events) {
                this._attachEvents();
            }

            this.draw();
        };
    }

    /**
     * Process and attach the bindings to the dashboard.
     *
     * @private
     */
    _attachBindings() {
        for (let binding of this.bindings) {
            let [controlWrapper, chartWrapper] = binding;

            this.gchart.bind(
                new google.visualization.ControlWrapper(
                    controlWrapper[0]
                ),
                new google.visualization.ChartWrapper(
                    chartWrapper[0]
                )
            );
        }
    }
}
