window.fbAsyncInit = function() {
    // FaceBook `like` clicked
    if(social_interaction_track_fb_like){
        FB.Event.subscribe('edge.create', function(targetUrl) {
            ga('send', 'social', 'facebook', 'like', targetUrl);
        });
    }

    // FaceBook `unlike` clicked
    if(social_interaction_track_fb_unlike){
        FB.Event.subscribe('edge.remove', function(targetUrl) {
            ga('send', 'social', 'facebook', 'unlike', targetUrl);
        });
    }

    /* @TODO: This one needs work! */
    if(social_interaction_track_fb_share){
        FB.Event.subscribe('message.send', function(targetUrl) {
            ga('send', 'social', 'facebook', 'send', targetUrl);
        });
    }
};
