function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

function startTime() {
    var today = new Date();
    var h = today.getHours();
    var timeofday = "AM";
    if (h > 12) {
        h = h - 12;
        timeofday = "PM";
    }
    var m = today.getMinutes();
    // add a zero in front of numbers<10
    m = checkTime(m);
    document.getElementById('time').innerHTML = h + ":" + m + " " + timeofday;
    t = setTimeout(function () {
        startTime()
    }, 500);
}

setTimeout(function () {
    startTime();
}, 1000);
