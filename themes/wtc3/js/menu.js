var nestableCount = 1;
$('.dd').nestable({maxDepth: 1});

$("#dd-list-app-categories-container .close").on("mousedown", removeOnClick);
function removeOnClick(event) {
    event.preventDefault();
    $.ajax({
        url: $(this).attr('href') + '?ajax',
        type: 'post',
        success: function () {
            location.href = location.href;
        }
    });

}
function menuSubmit()
{
    var data = $('.dd').nestable('serialize');
    console.log(JSON.stringify(data));

    $.ajax({
        url: 'menu/update',
        type: 'post',
        data: {'menuitems': JSON.stringify(data)},
        success: function () {
            location.href = location.href;
        }
    });


}

function footerCompaniesSubmit()
{
    var data = $('.dd').nestable('serialize');
    console.log(JSON.stringify(data));

    $.ajax({
        url: 'companiesUpdate',
        type: 'post',
        data: {'menuitems': JSON.stringify(data)},
        success: function () {
            location.href = location.href;
        }
    });


}

function footerCandidatesSubmit()
{
    var data = $('.dd').nestable('serialize');
    console.log(JSON.stringify(data));

    $.ajax({
        url: 'candidatesUpdate',
        type: 'post',
        data: {'menuitems': JSON.stringify(data)},
        success: function () {
            location.href = location.href;
        }
    });


}