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
            var input = $('input[name="' + name.replace('[attribute]', '[value]') + '"]');
            var selectedValue = element.val();

            var facetUrl = url.build('/tweakwise/ajax/facets/category/' + category_id);
            var foundSelectedValue = false;
            $.getJSON(facetUrl, function( data ) {
                element.empty();
                console.log(selectedValue);
                data.data.forEach(value => {
                    if(value.value != selectedValue) {
                        console.log('not found');
                        element.append($("<option></option>").attr("value", value.value).text(value.label));
                    } else {
                        console.log('found');
                        foundSelectedValue = true;
                        element.append($("<option></option>").attr("value", value.value).text(value.label).attr("selected", "selected"));
                    }
                });

                if (foundSelectedValue === false) {
                    element.prepend($("<option></option>").attr("value", selectedValue).text(selectedValue + " (Invalid value)").attr("selected", "selected"));
                }
            });

            this._bindEvents(name);
            element.trigger('change');
        },

        _bindEvents(name) {
            $('select[name="' + name + '"]').on('change', function(evt) {
                var name = evt.target.name;
                var facetValue = evt.target.value;
                var category_id = registery.get('emico_attributelanding_page_form.emico_attributelanding_page_form.general.category_id').value();
                var input = $('input[name="' + name.replace('[attribute]', '[value]') + '"]');
                var select = $('select[name="' + name.replace('[attribute-tmp]', '[value-tmp]') + '"]');

                //input.hide();

                if (!facetValue) {
                    select.empty();
                    select.prepend($("<option></option>").attr("value", input.val()).text(input.val() + " (Invalid value)").attr("selected", "selected"));
                } else {

                    var facetUrl = url.build('/tweakwise/ajax/facetattributes/category/' + category_id + '/facetkey/' + facetValue);
                    var foundSelectedValue = false;
                    $.getJSON(facetUrl, function (data) {
                        select.empty();
                        data.data.forEach(value => {
                            if (value.value != input.val()) {
                                select.append($("<option></option>").attr("value", value.value).text(value.label));
                            } else {
                                foundSelectedValue = true;
                                select.append($("<option></option>").attr("value", value.value).text(value.label).attr("selected", "selected"));
                            }
                        });

                        if (foundSelectedValue === false) {
                            select.prepend($("<option></option>").attr("value", input.val()).text(input.val() + " (Invalid value)").attr("selected", "selected"));
                        }
                    });
                }
            });
        }
    });

    return $.tweakwise.filter_attributes;
});
