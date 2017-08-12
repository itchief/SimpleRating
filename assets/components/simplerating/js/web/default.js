$(function () {
    var disabledVote = function () {
        $('.rating_active').removeClass('rating_active');
        $('.rating__best').removeClass('rating__best_hover');
        $('.rating__star').removeClass('rating__star_opacity rating__star_hover');
    };
    if (!navigator.cookieEnabled) {
        disabledVote();
    }
    var rating_star = $('.rating__star');
    rating_star.hover(function () {
        if ($('.rating').hasClass('rating_active')) {
            $(this).closest('.rating__best').addClass('rating__best_hover');
            $(this).closest('.rating__best').find('.rating__star').not(this).addClass('rating__star_opacity');
            $(this).addClass('rating__star_hover');
        }
    }, function () {
        if ($('.rating').hasClass('rating_active')) {
            $(this).closest('.rating__best').removeClass('rating__best_hover');
            $(this).closest('.rating__best').find('.rating__star').removeClass('rating__star_opacity');
            $(this).removeClass('rating__star_hover');
        }
    });
    rating_star.click(function (e) {
        e.preventDefault();
        if (!$(this).closest('.rating').hasClass('rating_active')) {
            return;
        }
        var title = $(this).attr('data-title');
        var id = $(this).closest('.rating__best').find('.rating__current').attr('data-id');
        $.post('/assets/components/simplerating/action.php', {'action': 'setRating', 'id': id, 'title': title})
            .done(function (data) {
                if (!$.isEmptyObject(data)) {
                    disabledVote();
                    var ratingValue = data['rating_value'];
                    var ratingCount = data['rating_count'];
                    var width = 130 * ratingValue / 5;
                    $('.rating__current').css('width', width);
                    $('.rating-count').text(ratingCount);
                    $('.rating-value').text(ratingValue.toFixed(1));
                }
            });
    });
});
