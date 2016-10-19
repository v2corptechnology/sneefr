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

    channel.bind('new_notification', newNotification);
}

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
