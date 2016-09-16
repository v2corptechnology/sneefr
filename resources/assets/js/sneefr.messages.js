var MessagingWidget = {
    settings: {
        fixedHeightNodes: $('.vertical-list'),
        activeListItem: $('.vertical-list-item.active'),
        messageHead: $('.js-message-head'),
        fixedStartingAtWidth: 991,
        delayBeforeRead: 2500,
    },

    init: function () {
        if (this.settings.fixedHeightNodes.length) {
            this.bindUIActions();
            this.resizeLists();
            this.removeActiveStateForResponsive();
        }
    },

    bindUIActions: function () {
        $(window).on('resize', this.resizeLists);
        $(window).on('resize', this.removeActiveStateForResponsive);
        MessagingWidget.settings.fixedHeightNodes.dontScrollParent();
        setTimeout(this.markRead, MessagingWidget.settings.delayBeforeRead);
    },

    resizeLists: function (event) {
        MessagingWidget.settings.messageHead.width($('.js-messages-container').width());
        if ($(document).width() > MessagingWidget.settings.fixedStartingAtWidth) {
            MessagingWidget.settings.fixedHeightNodes.each(function () {
                var $column = $(this);
                $column.height($(window).height() - $('.navbar-default').height())
                    .css('position', 'fixed')
                    .width($column.parent().width());
            });
        } else {
            MessagingWidget.settings.fixedHeightNodes.css({
                'height': 'auto',
                'width': 'auto',
                'position': 'static'
            });
        }
    },

    removeActiveStateForResponsive: function (event) {
        if ($(document).width() <= MessagingWidget.settings.fixedStartingAtWidth) {
            MessagingWidget.settings.activeListItem.removeClass('active');
        }
    },

    markRead: function(event) {
        var answer = $('.js-answer')[0],
            $discussionBadge = $('.js-discussion-' + answer.getAttribute('data-js-discussion-id') + ' .js-notification-badge'),
            discussionHasUnread = !$discussionBadge.hasClass('hidden');

        if (discussionHasUnread && $(document).width() > MessagingWidget.settings.fixedStartingAtWidth) {
            var $messageBadge = $('.js-messages.active .js-notification-badge');

            // Mark my messages as read
            $.ajax({url: answer.getAttribute('data-js-mark-read-url'), type: 'PUT'});

            // Update counts in navbar
            updateBadgeCount($messageBadge, -1);

            // Reset the discussion's badge
            updateBadgeCount($discussionBadge, 0);
        }
    }
};

$.fn.dontScrollParent = function()
{
    this.bind('mousewheel DOMMouseScroll',function(e)
    {
        var delta = e.originalEvent.wheelDelta || -e.originalEvent.detail;

        if (delta > 0 && $(this).scrollTop() <= 0)
            return false;
        if (delta < 0 && $(this).scrollTop() >= this.scrollHeight - $(this).height())
            return false;

        return true;
    });
}

$(function() {
    MessagingWidget.init();
})
