var tags        = [],
    postTags    = [];

var Tag         = {
    init : function(config) {
        this.config = config;

        this.bindEvents();
        this.fetchTags();
        this.checkPostTags();
    },

    bindEvents : function() {
        $(document)
            .on('click', this.hideSuggestions)
            .on('click', '.post-tags-suggestions li a', this.clickTag)
            .on('click', '.remove-tag', this.removeSelectedTag);

        $('#tag_input').on('keypress', this.showSuggestions);
    },

    /**
     * Fetch tags
     */
    fetchTags : function() {
        // trigger ajax call
        $.ajax({
            type : 'get',
            url : '/api/v1/tags/all',
            dataType : 'json'
        }).done(function(response) {
            if(response.data) {
                var savedTags = response.data.tags;
                $.each(savedTags, function(i, tag) {
                    tags.push(tag.tag);
                });
            }
        });

        return;
    },

    /**
     * Checks the hidden input element if it has content/value
     */
    checkPostTags : function() {
        var tags = $('input[name="tags"]');
        // check if it's not empty
        if(tags.val().length != 0) {
            // get value
            $.each(tags.val().split(','), function(i, tag) {
                Tag.addTag(tag);
            });
        }
    },

    /**
     * Shows tag suggestions
     */
    showSuggestions : function(e) {
        var $this           = $(this),
            code            = e.keyCode || e.which,
            tagsSuggestions = $('.post-tags-suggestions'),
            suggestions     = [];

        // value not empty
        if($this.val().length != 0) {
            tagsSuggestions.removeAttr('style');
            // check if value is in the suggestions
            for(var i = 0; i < tags.length; i++) {
                if(tags[i].indexOf($this.val()) != -1) {
                    if($.inArray(tags[i], postTags) == -1) {
                        suggestions.push(tags[i]);
                    }
                }
            }

            // show suggestions
            if(suggestions.length != 0) {
                Tag.renderSuggestions(suggestions);
            } else {
                tagsSuggestions.hide().empty().parent()
                    .removeClass('open');
            }

            // enter key is triggered so the current value will be added as tag
            // as long as the value is not yet added
            if(code == 13 && $.inArray($this.val(), postTags) == -1) {
                // add
                Tag.addTag($this.val());
                Tag.addToTags($this.val());
                // close suggestions if opened
                tagsSuggestions.hide().empty().parent()
                    .removeClass('open');
                // empty the input
                $this.val('');
            }

            return;
        }

        // value is empty and backspace is triggered
        if(code == 8 && $this.val().length == 0) {
            // remove last item in the list of tags to be added
            var lastTag = $('.tags-wrapper span:last-child');
            if(lastTag.length != 0) {
                Tag.removeTag(lastTag.data('tag'));
                lastTag.remove();
            }

            return;
        }
    },

    /**
     * Hide suggestions
     */
    hideSuggestions : function() {
        if($('.post-tags-suggestions').is(':visible')) {
            $('.post-tags-suggestions').hide().empty()
                .parent().removeClass('open');
        }

        return;
    },

    /**
     * Renders the suggestions to HTML
     */
    renderSuggestions : function(suggestions) {
        var tagsSuggestions = $('.post-tags-suggestions');
        // check if suggestions is empty
        if(suggestions.length != 0) {
            tagsSuggestions.empty();

            $.each(suggestions, function(i, suggestion) {
                tagsSuggestions.prepend('<li><a href="#" data-tag="'+suggestion+'">'+
                suggestion+'</a></li>');
            });

            tagsSuggestions.parent().addClass('open');
        }

        return;
    },

    /**
     * Selects a suggested tag
     */
    clickTag : function(e) {
        e.preventDefault();

        var tag = $(this).data('tag');
        // empty the input
        $('#tag_input').val('');
        // remove contents and hide the suggestions
        $('.post-tags-suggestions').hide().empty()
            .parent().removeClass('open');

        Tag.addTag(tag);
        return;
    },

    /**
     * Removes an added tag
     */
    removeSelectedTag : function(e) {
        e.preventDefault();
        var $this = $(this),
            tag = $this.parent().data('tag');

        Tag.removeTag(tag);
        // remove from the DOM
        $this.parent().remove();
        return;
    },

    /**
     * Adds a tag
     */
    addTag : function(tag) {
        var tagsWrapper = $('.tags-wrapper');

        tagsWrapper.append('<span class="tag" data-tag="'+tag+'"><i class="fa fa-tag"></i> '+
        tag+'<a href="#" class="remove-tag"><i class="fa fa-close"></i></a></span>');
        // add tag to an array
        postTags.push(tag);
        // set input tag
        Tag.setInputTags();
        return;
    },

    /**
     * Removes a tag
     */
    removeTag : function(tag) {
        // remove tag
        postTags.splice($.inArray(tag, postTags), 1);

        Tag.setInputTags();
        return;
    },

    /**
     * Set the value for the hidden input element for tags
     */
    setInputTags : function() {
        var hiddenTags = '',
            inputTag = $('input[name="tags"]');

        // put tags to the hidden element
        $.each(postTags, function(i, tag) {
            hiddenTags += (i == 0) ? tag : ','+tag;
        });

        inputTag.val(hiddenTags);
    },

    /**
     * User inputted tags to be added also
     */
    addToTags : function(tag) {
        // check if tag exists in the tags array
        if($.inArray(tag, tags) == -1) {
            // add to the tags
            tags.push(tag);
        }

        return;
    }
};

// run this shit
Tag.init();
