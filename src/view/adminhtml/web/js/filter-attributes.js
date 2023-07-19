define([
    'jquery',
    'uiRegistry',
    'mage/url'
], function($, registery, url) {
    'use strict';

    $.widget('mage.tweakwise_filter_attributes', {
        /**
         * Bind handlers to events
         */
        _create: function() {
            var category_id = registery.get('emico_attributelanding_page_form.emico_attributelanding_page_form.general.category_id').value();
            var element = this.element;

            var facetUrl = url.build('/tweakwise/ajax/facets/category/' + category_id);
            $.getJSON(facetUrl, function( data ) {
                element.empty();
                data.data.forEach(value => {
                    element.append($("<option></option>").attr("value", value.value).text(value.label));
                });
            });

            element.on('change', this.updateAttributeValues());
        },

        updateAttributeValues: function () {
            var element = this.element;
            var facetValue = element.val();
            var category_id = registery.get('emico_attributelanding_page_form.emico_attributelanding_page_form.general.category_id').value();

            var facetUrl = url.build('/tweakwise/ajax/facetattributes/category/' + category_id + '/facetkey/' + facetValue);
            $.getJSON(facetUrl, function( data ) {
                console.log(data);
            });
        }
    });

    return $.mage.tweakwise_filter_attributes;
});
