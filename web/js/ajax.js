// AJAX Calls
$('#addFriend').on('click', function () {
    $.ajax({
        url: Routing.generate('add-Friend'),
        type: 'POST',
        data: {
            userId: user
        }
    }).done(function(data){
        $('#addFriend').attr('disabled', 'disabled');
        $('#addFriend').text('Requête envoyée !');
    });
});

$('.acceptFriendRequest').on('click', function () {
    $.ajax({
        url: Routing.generate('accept-request'),
        type: 'POST',
        context: this,
        data: {
            userId: $(this).attr('data-user')
        }
    }).done(function(data){
        location.reload()
    });
});

$('.removeFriendRequest').on('click', function () {
    $.ajax({
        url: Routing.generate('remove-request'),
        type: 'POST',
        context: this,
        data: {
            userId: $(this).attr('data-user')
        }
    }).done(function(data){
        location.reload()
    });
});

$(document).on('click', '.addFavorite', function () {
    $.ajax({
        url: Routing.generate('add-favorite'),
        type: 'POST',
        context: this,
        data: {
            lat: $(this).attr('data-lat'),
            lng: $(this).attr('data-lng'),
            name: $(this).attr('data-name'),
            map_id: $(this).attr('data-map-id')
        }
    }).done(function(data){
        $(this).attr('data-id', data);
        $(this).children().text("star");
        $(this).removeClass('addFavorite');
        $(this).addClass('removeFavorite');
    });
});

$(document).on('click', '.removeFavorite', function () {
    $.ajax({
        url: Routing.generate('remove-favorite'),
        type: 'POST',
        context: this,
        data: {
            id: $(this).attr('data-id')
        }
    }).done(function(data){
        $(this).closest('tr').fadeOut();
        $(this).children().text("star_border");
        $(this).removeClass('removeFavorite');
        $(this).addClass('addFavorite');
    });
});

$(document).on('click', '.removeProfileFavorite', function () {
    $.ajax({
        url: Routing.generate('remove-favorite'),
        type: 'POST',
        context: this,
        data: {
            id: $(this).attr('data-id')
        }
    }).done(function(data){
        $(this).parent().parent().fadeOut();
    });
});


$('#removeFriend').on('click', function () {
    $.ajax({
        url: Routing.generate('remove-Friend'),
        type: 'POST',
        data: {
            userId: user
        }
    }).done(function(data){
        location.reload();
    });
});

$(document).on('click', '.joinEvent', function() {
    let event = this;
    $.ajax({
        url: Routing.generate('join-event'),
        type: 'POST',
        data: {
            eventId: $(this).attr('data-id')
        }
    }).done(function(data){
        $(event).replaceWith('<img src="/uploads/users/' + data + '" class="avatar">');
        $("#tooltip-participate").remove()
    });
});

$('.quitEvent').on('click', function() {
    let event = this;
    $.ajax({
        url: Routing.generate('quit-event'),
        type: 'POST',
        data: {
            eventId: $(this).attr('data-id')
        }
    }).done(function(data){
        let id = $(event).attr('data-id');
        $('#user' + data).remove();
        $(event).remove();
        $('#tooltip-leave').remove();
    });
});

function addCitiesToStats(dataInput) {
    $.ajax({
        url: Routing.generate('update-stats-cities'),
        type: 'POST',
        data: {
            cities: dataInput.count
        }
    }).done(function(data){

    });
}
