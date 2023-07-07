define([
    'Magento_Ui/js/form/element/ui-select',
    'jquery'
], function (Select) {
    'use strict';

    return Select.extend({

        initialize: function () {
            this._super();
            this.onUpdate(this.value());
            return this;
        },

        /**
         * Parse data and set it to options.
         *
         * @param {Object} data - Response data object.
         * @returns {Object}
         */
        setParsed: function (data) {
            var option = this.parseData(data);

            if (data.error) {
                return this;
            }

            this.options([]);
            this.setOption(option);
            this.set('newOption', option);
        },

        /**
         * Normalize option object.
         *
         * @param {Object} data - Option object.
         * @returns {Object}
         */
        parseData: function (data) {
            return {
                'is_active': data.category['is_active'],
                level: data.category.level,
                value: data.category['entity_id'],
                label: data.category.name,
                parent: data.category.parent
            };
        },

        /**
         * Change currently selected option
         *
         * @param {String} id
         */
        onUpdate: function(value){
            if((value == '')||(value == undefined)) {
                setTimeout(function() {
                    document.querySelector('.filter_attributes').hide();
                }, 1000);
            } else {
                document.querySelector('.filter_attributes').show();
            }
        },
    });
});
