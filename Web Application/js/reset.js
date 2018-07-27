var count = 10;
var countdown = setInterval(function () {
    $("p#countdown").html("Returning to menu in " + count + " seconds.");
    if (count == 0) {
        clearInterval(countdown);
        window.open('php/reset.php', "_self");

    }
    count--;
}, 1000);
