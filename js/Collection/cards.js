$(function() {
    'use strict';

    Dingbat.Collection.Cards = Backbone.Collection.extend({

        model: Dingbat.Model.Card,

        url: 'index.php/cards'

    });

});

