import { Application, MnObject } from 'backbone.marionette';

export default MnObject.extend({
    regions: {},

    /**
     * @property {App} App
     */
    app: null as typeof Application,

    constructor (options, App: Application) {
        this.app = App;

        MnObject.call(this, options);
    }
});
