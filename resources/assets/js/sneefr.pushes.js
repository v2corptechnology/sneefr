// Retrieve pusher configuration
var pusherKey = $('meta[name="_pusher_key"]').attr('content'),
    pusherChannel = $('meta[name="_pusher_channel"]').attr('content'),
    csrfToken = $('meta[name="_token"]').attr('content'),
    pusherCluster = $('meta[name="_pusher_cluster"]').attr('content');


var updateBadgeCount = function ($badge, number) {
    var count = parseInt($badge.text()),
        finalCount;

    // If no badge or null number
    if (!$badge.length) {
        return;
    }

    if (number != 0) {
        finalCount = count + number;
    } else {
        number = count * -1;
        finalCount = 0;
    }

    $badge.text(finalCount).toggleClass('hidden', finalCount <= 0);

    // Update responsive counter
    updateTotalNotificationCount(number);
};


/**
 * Get the number of notifications to add/remove as input
 * and updates the title accordingly
 *
 * @param int num
 *
 * @retrun int
 */
function updateTotalNotificationCount(num) {
    var count = 0,
        title = document.title,
        isAlreadyNotified = /\([\d]+\)/.test(title),
        badge = document.querySelector('.navbar-toggle-menu .notification-badge');

    if (isAlreadyNotified) {
        count = title.match(/\([\d]+\)/)[0];
        count = parseInt(count.match(/[\d]+/)) + num;
    } else {
        count = num;
    }

    // If we have a positive count, display notification
    if (count) {
        // Check if we already have a counter
        if (isAlreadyNotified) {
            document.title = title.replace(/\([\d]+\)/, '(' + count + ')');
        } else {
            document.title = '(' + count + ') ' + title;
        }

        if (badge) {
            badge.innerHTML = count;
        } else {
            document.querySelector('.navbar-toggle-menu').insertAdjacentHTML('afterbegin', '<sup class="notification-badge">' + count + '</sup>');
        }

    } else {
        if (isAlreadyNotified) {
            title = title.split(') ');
            document.title = title[1];
        }

        if (badge) {
            badge.parentElement.removeChild(badge);
        }
    }
}

// Start channel only when one is filled
if (pusherChannel) {
    var channel = new Pusher(pusherKey, {
        authEndpoint: '/pusherAuth',
        cluster: pusherCluster,
        auth: {headers: {'X-CSRF-TOKEN': csrfToken}}
    }).subscribe(pusherChannel);

    channel.bind('sent_message', addMessage);
    channel.bind('new_message', newMessage);
    channel.bind('new_notification', newNotification);
    channel.bind('updated_ad', adWasUpdated);
}

// Add a message to the current discussion
function addMessage (data) {
    // Create the node
    var $message = $(data.message_body);

    // Insert it at the end of the discussion
    $('.js-latest').before($message);

    // Refresh "ago" timestamps
    TimeagoWidget.init();

    // Scroll to the bottom
    window.scrollTo(0, document.body.scrollHeight);

    // Put the focus in the textarea
    $('.js-answer-body').focus();
}

// An new message is received
function newMessage(data) {
    // Grab the main notification badge
    var $topBadge = $('.js-pushes-target-' + data.target + ' .js-notification-badge');

    // Update count of the badge in navbar
    updateBadgeCount($topBadge, 1);

    // Grab the discussion the notification targets
    var $targetDiscussion = $('.js-discussion-' + data.discussion_id);
    // Grab the current discussion ID
    var $currentDiscussion = $('.js-messages-container').attr('data-discussion-id');

    // If we can reach the targeted discussion
    if ($targetDiscussion.length) {

        // Grab the badge related to this discussion
        var $discussionBadge = $('.js-notification-badge', $targetDiscussion);

        // Update count of the badge in discussions list
        updateBadgeCount($discussionBadge, 1);

        // If the targeted discussion is the current one
        if ( data.discussion_id == $currentDiscussion) {

            // Add the message to the discussion
            addMessage(data);

            // Answer form
            var $answerZone = $('.js-answer-body');

            // Unlock the reply zone if necessary
            $answerZone.prop('disabled', false).placeholder = $answerZone.data('placeholder-unlocked');

            // Mark it read as soon as displayed
            MessagingWidget.markRead();
        }
    }
}

function adWasUpdated(data) {
    var $container = $('.js-messages-container');

    // Check if we are on the discussions screen
    if ($container.length) {
        // Remove the ad node from the discussion
        $('.js-ad-' + data.ad_id).remove();

        // Scroll to the bottom
        window.scrollTo(0, document.body.scrollHeight);

        // Fetch the latest status for this ad
        $.get(
            data.update_route,
            {'discussion_id' : $container.data('discussion-id')},
            function (response) {
                // Insert it at the end of the discussion
                $('.js-latest').before(response.fragment);

                // Refresh "ago" timestamps
                TimeagoWidget.init();

                // Scroll to the bottom
                window.scrollTo(0, document.body.scrollHeight);

                // Put the focus in the textarea
                $('.js-answer-body').focus();
        });
    }
};

var newNotification = function (data) {
    var $notificationsBadge = $('.js-notifications .js-notification-badge');

    updateBadgeCount($notificationsBadge, 1);
}

/*
 // Enable pusher logging - don't include this in production
 Pusher.log = function(message) {
 console.log(message);
 };
*/
