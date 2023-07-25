define([
    'jquery',
    'uiRegistry',
    'mage/url',
], function($, registery, url) {
    'use strict';

    $.widget('tweakwise.filter_attributes', {
        /**
         * Bind handlers to events
         */
        _create: function(config, element) {
            this._updateAttributes(this.options.name);
        },

        _updateAttributes: function (name) {
            var category_id = registery.get('emico_attributelanding_page_form.emico_attributelanding_page_form.general.category_id').value();
            var element =  $('select[name="' + name + '"]');
            var selectedValue = element.val();

            var facetUrl = url.build('/tweakwise/ajax/facets/category/' + category_id);
            $.getJSON(facetUrl, function( data ) {
                element.empty();
                data.data.forEach(value => {
                    if(value.value != selectedValue) {
                        element.append($("<option></option>").attr("value", value.value).text(value.label));
                    } else {
                        element.append($("<option></option>").attr("value", value.value).text(value.label).attr("selected", "selected"));
                    }

                });
            });

            this._bindEvents(name);
        },

        _bindEvents(name) {
            $('select[name="' + name + '"]').on('change', function(evt) {
                var name = evt.target.name;
                var facetValue = evt.target.value;
                var category_id = registery.get('emico_attributelanding_page_form.emico_attributelanding_page_form.general.category_id').value();
                var select = $('select[name="' + name.replace('[attribute]', '[value]') + '"]');
                var selectedValue = select.val();

                console.log(selectedValue);

                var facetUrl = url.build('/tweakwise/ajax/facetattributes/category/' + category_id + '/facetkey/' + facetValue);
                $.getJSON(facetUrl, function( data ) {
                    select.empty();
                    data.data.forEach(value => {
                        select.append($("<option></option>").attr("value", value.value).text(value.label));
                    });
                });
            });

            $('select[name="' + name + '"]').trigger('change');
        }
    });

    return $.tweakwise.filter_attributes;
});
