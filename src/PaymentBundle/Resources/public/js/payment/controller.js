import Backbone from 'backbone';
import $ from 'jquery';
import { View } from 'backbone.marionette';
import Template from '../../templates/loader.hbs';
import { isEmpty } from 'lodash';
import Router from 'router';

const LoaderView = View.extend({
    template: Template
});

export default (module, model) => {
    return {
        showMethod (routeFragment) {
            let fragment = Backbone.history.getFragment();

            if (isEmpty(fragment)) {
                fragment = routeFragment;
            }

            $('a', '#payment-method-tabs').removeClass('active');
            $(`a[data-method="${fragment}"]`).closest('a').addClass('active');

            const route = Router.generate('_xhr_payments_settings', { 'method': fragment });
            module.app.showChildView('paymentMethodData', new LoaderView);

            $.get(route, (response) => {
                const view = View.extend({
                    template: () => {
                        return response;
                    },
                    ui: {
                        'save': '#payment_methods_save'
                    },
                    events: {
                        'click @ui.save': 'saveMethod'
                    },
                    saveMethod (event) {
                        event.preventDefault();

                        module.app.showChildView('paymentMethodData', new LoaderView);

                        const form = this.$('form'),
                            data = form.serialize(),
                            url = form.prop('action');

                        $.ajax({
                            url: url,
                            data: data,
                            method: 'POST',
                            success (res) {
                                module.app.showChildView('paymentMethodData', new view({
                                    template: () => res
                                }));
                                model.fetch();
                            }
                        });
                    }
                });

                module.app.showChildView('paymentMethodData', new view);
            });
        }
    };
};
