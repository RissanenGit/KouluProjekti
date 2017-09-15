
//TODO:
//Otetaan muuttujien oletusarvot sivun latauksen yhteydessä
//Menupalkin siirtyminen jos sivu on liian korkea

var startOffset = 0;
var changeWidth = 753;
window.addEventListener("load", pageFullyLoaded, false);

//Ikkunan kokoa muutetaan
$(window).resize(function () {
    if ($(window).width() < changeWidth) {
        // $(".col-sm-2").css("height", $(".lista").height() + 20);
        $("#rottisTeksti").css("font-size", 25);
        $(".lista").css("top", 0);
    } else {
        //$(".col-sm-2").css("height", $(".col-sm-10").innerHeight());
        $("#rottisTeksti").css("font-size", 36);
    }
});

$(window).scroll(function () {
    //Valikon siirtäminen sivun mukana
    moveMenu();
});
function moveMenu() {
    if ($(window).width() > changeWidth) {
        $(".lista").clearQueue();
        var scrollPosition = $(window).scrollTop();
        if (scrollPosition > 0 && (scrollPosition + 10) - startOffset > 0) {
            $(".lista").animate({top: (scrollPosition + 10) - startOffset}, 600);
        } else {
            $(".lista").animate({top: 0}, 600);
        }
    }

}

function pageFullyLoaded() {
    if ($(window).width() > changeWidth) {
        //    $(".col-sm-2").css("height", $(".col-sm-10").height());
    } else {
        $("#rottisTeksti").css("font-size", 25);
        //    $(".col-sm-2").css("height", $(".lista").height() + 20);
    }
    startOffset = $(".lista").offset().top;
    moveMenu();
}


