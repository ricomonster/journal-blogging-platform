var Editor = {
    showdown 	: '',
    codemirror 	: '',
    /**
     * This will run once page loads
     */
    init : function() {
        // bind the events
        this.bindEvents();

        // initialize these things
        this.showdown = new Showdown.converter;
        this.codemirror = CodeMirror.fromTextArea(document.getElementById("the_editor"), {
            mode: "markdown",
            tabMode: "indent",
            lineWrapping: !0
        });

        // listens once code mirror content changes
        this.codemirror.on('change', function() {
            Editor.updatePreview();
        });

        // update the preview
        Editor.updatePreview();
        // sync scroll
        $(".CodeMirror-scroll").on("scroll", Editor.syncScroll);
    },
    /**
     * All events are listened here
     */
    bindEvents : function() {
        $(document)
            .on('blur', 'input[name="title"]', this.createSlug)
            .on('click', '.set-status', this.setDropdownPostStatus)
            .on('click', '.delete-this-post', this.showModalToDeletePost)
            .on('click', '.delete-now', this.deletePost)
            .on('click', '#submit_post', this.submitPost);
    },
    /**
     * Updates the preview once the user starts typing or once the page loads
     */
    updatePreview : function() {
        var showdown = this.showdown,
            codemirror = this.codemirror,
            renderedMarkdown = $('#rendered_markdown');

        // get the rendered html
        var renderedHtml = showdown.makeHtml(codemirror.getValue());
        // replace the scripts and iframes
        renderedHtml = renderedHtml.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
            '<div class="embedded-javascript">Embedded JavaScript</div>');
        renderedHtml = renderedHtml.replace(/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi,
            '<div class="embedded-iframe">Embedded iFrame</div>');

        // show results
        renderedMarkdown.html(renderedHtml);
        // apply to markdown value to the textarea
        $('#the_editor').val(codemirror.getValue());
        // count the words

        return;
    },
    /**
     * Submit the posts to the API
     */
    submitPost : function(e) {
        e.preventDefault();

        $.ajax({
            type : 'post',
            url : '/api/v1/posts/save',
            data : {
                author_id       : $('input[name="author_id"]').val(),
                title           : $('input[name="title"]').val(),
                content         : $('textarea[name="content"]').val(),
                post_id         : $('input[name="post_id"]').val(),
                slug            : $('input[name="slug"]').val(),
                tags            : $('input[name="tags"]').val(),
                status          : $('input[name="status"]').val(),
                publish_date    : Editor.setupPublishDate()
            },
            dataType : 'json'
        }).done(function(response) {
            if(response.data) {
                var post = response.data.post;
                // populate inputs
                $('input[name="title"]').val(post.title);
                $('input[name="slug"]').val(post.slug);
                // update ui
                Editor.setSubmitButton();
                // show success message
                //notification(response.data.message, 'success');
                // show delete link
                $('.delete-this-post').show();
                // update post id
                $('input[name="post_id"]').val(post.id);
                // update post status
                $('input[name="post_status"]').val(post.status);
            }
        }).error(function(error) {
            //notification(error.responseJSON.errors.message, 'danger');
        });
    },
    /**
     * Creates the slug for the post by calling the API
     */
    createSlug: function(e) {
        e.preventDefault();
        var $this = $(this);

        // do not trigger call if value is empty
        if($this.val().length == 0) {
            return false;
        }

        // trigger ajax call
        $.ajax({
            type : 'get',
            url : '/api/v1/posts/get_slug',
            data : {
                string 	: $this.val(),
                id 		: $('input[name="post_id"]').val()
            }
        }).done(function(response) {
            if(response.data) {
                $('input[name="slug"]').val(response.data.slug);
            }
        });

        return;
    },
    /**
     * Shows the modal to confirm on deleting a post
     */
    showModalToDeletePost : function(e) {
        e.preventDefault();
        var postTitle = $('input[name="title"]').val();

        $('#delete_posts_modal').modal('show').find('.post-title').text(postTitle);
        // attach the post id
        $('#delete_posts_modal').find('input[name="post_id"]')
            .val($('input[name="post_id"]').val());
        return;
    },
    /**
     * Deletes the post
     */
    deletePost : function(e) {
        e.preventDefault();
        var $this = $(this),
            postId = $('#delete_posts_modal').find('input[name="post_id"]').val();

        // validate first if the id is 0
        if(postId == 0) {
            // show notification
            notification('Something went wrong. Please try again later.', 'danger');
            // close modal
            $('#delete_posts_modal').modal('hide');
            return;
        }

        // disable buttons
        $('#delete_posts_modal').find('.btn').addClass('btn-disabled')
            .attr('disabled', 'disabled');

        // trigger ajax request
        $.ajax({
            type : 'post',
            url : '/api/v1/post/delete',
            data : {
                post_id : postId
            },
            dataType : 'json'
        }).done(function(response) {
            if(response.data) {
                // redirect
                window.location.href = response.data.redirect_url;
            }
        });

        return;
    },
    /**
     * Sets the status of the post
     */
    setDropdownPostStatus : function(e) {
        e.preventDefault();
        var $this = $(this),
            status = 2,
            postStatusWrapper = $('.post-status'),
            option = $this.data('status');

        switch(option) {
            case 1 :
                postStatusWrapper.find('.btn').removeClass('btn-primary')
                    .addClass('btn-danger');
                postStatusWrapper.find('button[type="submit"]')
                    .text('Publish Post');
                status = 1;
                break;
            case 2 :
                postStatusWrapper.find('.btn').removeClass('btn-danger')
                    .addClass('btn-primary');
                postStatusWrapper.find('button[type="submit"]')
                    .text('Save as Draft');
                status = 2;
                break;
            case 3 :
                postStatusWrapper.find('.btn').removeClass('btn-primary')
                    .addClass('btn-danger');
                postStatusWrapper.find('button[type="submit"]')
                    .text('Unpublish Post');
                status = 2;
                break;
            case 4 :
                postStatusWrapper.find('.btn').removeClass('btn-danger')
                    .addClass('btn-info');
                postStatusWrapper.find('button[type="submit"]')
                    .text('Update Post');
                status = 1;
                break;
        }

        // set the status
        $('input[name="status"]').val(status);
        // unset the current active
        $('.post-status').find('.dropdown-menu')
            .find('li.active')
            .removeClass('active');
        // set active
        $this.parent().addClass('active');
        return;
    },

    /**
     * Updates the button
     */
    setSubmitButton : function() {
        var selectedOption  = $('.post-status').find('.dropdown-menu')
                .find('li.active').find('a.set-status').data('status'),
            button          = $('.post-status').find('button[type="submit"]'),
            dropdownToggle  = $('.post-status').find('button.dropdown-toggle'),
            dropdown        = $('.post-status').find('.dropdown-menu');

        switch(selectedOption) {
            case 1 :
                button.removeClass('btn-danger btn-primary').addClass('btn-info')
                    .text('Update Post');
                dropdownToggle.removeClass('btn-danger btn-primary')
                    .addClass('btn-info');
                dropdown.empty().append('<li>' +
                '<a href="#" class="set-status" data-status="3">Unpublish Post</a>' +
                '</li><li class="active">' +
                '<a href="#" class="set-status" data-status="4">Update Post</a></li>');
                break;
            case 3 :
                button.removeClass('btn-danger btn-info').addClass('btn-primary')
                    .text('Save as Draft');
                dropdownToggle.removeClass('btn-danger btn-info')
                    .addClass('btn-primary');
                dropdown.empty().append('<li>' +
                '<a href="#" class="set-status" data-status="1">Publish Now</a>' +
                '</li><li class="active">' +
                '<a href="#" class="set-status" data-status="2">Save as Draft</a></li>');
                break;
        }

        return;
    },
    /**
     * Validates and fixes the publish date of the post
     */
    setupPublishDate : function() {
        var currentDate     = new Date(),
            month           = $('select[name="month"] :selected'),
            day             = $('input[name="day"]'),
            year            = $('input[name="year"]'),
            hour            = $('input[name="hour"]'),
            minute          = $('input[name="minute"]'),
            postSeconds     = ("0" + currentDate.getSeconds()).slice(-2);

        // validate month
        var postMonth = (month.val().length == 0 || parseInt(month.val()) > 12) ?
                ('0' + (currentDate.getMonth() + 1)).slice(-2) : month.val(),
        // validate day
            postDay = (day.val().length == 0 || parseInt(day.val()) > 32) ?
                ('0' + currentDate.getDate()).slice(-2) : day.val(),
        // validate year
            postYear = (year.val().length != 4 || !year.val().match(/\d{4}/) ||
            (parseInt(year.val()) > currentDate.getFullYear() + 1 || parseInt(year.val()) < 1900)) ?
                currentDate.getFullYear() : year.val();
        // validate hour
        var postHour;
        if(hour.val().length == 0 || parseInt(hour.val()) > 23) {
            postHour = ("0" + currentDate.getHours()).slice(-2);
        } else if(hour.val().length == 1) {
            postHour = "0"+hour.val();
        } else {
            postHour = hour.val();
        }

        // validate minute
        var postMinute;
        if(minute.val().length == 0 || parseInt(minute.val()) > 59) {
            postMinute = ("0" + currentDate.getMinutes()).slice(-2);
        } else if(minute.val().length == 1) {
            postMinute = "0"+minute.val();
        } else {
            postMinute = minute.val();
        }

        // update inputs

        return postYear+"-"+postMonth+"-"+postDay+" "+postHour+":"+postMinute+":"+postSeconds;
    },

    syncScroll: function(t) {
        var e = $(t.target),
            a = $(".entry-preview-content"),
            i = $(".CodeMirror-sizer"),
            o = $("#rendered_markdown"),
            n = i.height() - e.height(),
            s = o.height() - a.height(),
            r = s / n,
            d = e.scrollTop() * r;
        a.scrollTop(d)
    }
};

// run
Editor.init();
