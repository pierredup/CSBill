/*
 * This file is part of SolidInvoice project.
 *
 * (c) 2013-2017 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

import Backgrid from 'backgrid';

Backgrid.Extension.MoneyCell = Backgrid.StringCell.extend({
    render () {
        this.$el.empty();

        const name = this.column.get('name'),
            value = this.model.get(name + '.value'),
            currency = this.model.get(name + '.currency');

        if (currency) {
            this.$el.text(this.formatter.fromRaw({ 'value': value, 'currency': currency }, this.model));
        } else {
            // We don't have a currency set, just display the raw value
            this.$el.text(( parseInt(this.model.get(name), 10) / 100 ).toFixed(2));
        }

        this.updateStateClassesMaybe();
        this.delegateEvents();
        return this;
    }
});

export default Backgrid.Extension.MoneyCell;
