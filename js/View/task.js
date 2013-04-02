$(function() {
    'use strict';

    Dingbat.View.Task = Backbone.View.extend({

        events: {
            'click .update':   'showForm',
            'dblclick label':  'showForm',
            'click .cancel':   'hideForm',
            'click .delete':   'delete',
            'click .checkbox': 'toggleMarked',
            'keydown :text':     'cancel',
            'submit form':     'update'
        },


        model: null,


        tagName: 'div',


        template: _.template($('#task-template').html()),


        cancel: function(event) {
            if (event.which == 27) {
                this.hideForm();
            }
        },


        delete: function() {
            this.model.destroy();

            this.$el.slideUp(function() {
                this.remove();
            })
        },


        hide: function() {
            this.$el.slideUp(function() {
                $(this).appendTo('body');
            });
        },


        hideForm: function() {
            this.$(':text.name').hide();
            this.$('.cancel').hide();
            this.$('.update').show();
            this.$('label').show();
        },


        initialize: function() {
            var view = this;

            this.listenTo(this.model, 'change:marked', function(model) {
                if (model.get('marked') == 1) {
                    view.mark();
                }
                else {
                    view.unmark();
                }
            });
        },


        mark:  function() {
            this.model.set('marked', 1);

            this.$('.checkbox').addClass('checked');
            this.$('.name').wrap('<del />');
        },


        render: function() {
            this.$el.html(this.template(this.model.toJSON())).hide();
            this.$el.attr('class', 'prio-' +  this.model.priorityName);
            this.hideForm();

            if (this.model.get('marked') == 1) {
                this.mark();
            }

            return this;
        },


        show: function() {
            this.$el.slideDown();
        },


        showForm: function() {
            this.$('label').hide();

            this.$('.update').hide();
            this.$(':text.name').val(this.model.get('name')).show();
            this.$(':text').focus();
            this.$('.cancel').show();
        },


        toggleMarked: function() {
            if (this.model.get('marked') == 1) {
                this.unmark();
            }
            else {
                this.mark();
            }

            this.model.save();
        },


        unmark: function() {
            this.model.set('marked', 0);

            this.$('.checkbox').removeClass('checked');
            this.$('.name').unwrap();
        },


        update: function() {
            this.model.set('name', this.$(':text:first').val());

            if (this.model.save()) {
                this.$('.name').text( this.model.get('name') );
                this.$el.attr('class', 'prio-' + this.model.priorityName);
                this.hideForm();
            }

            return false;
        }

    });

})

