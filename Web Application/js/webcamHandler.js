/*webcamHandler.js
Responsible for initializing the webcam, setting the webcam's properties, and button management.*/

var imageData;

//Initialize the webcam.
Webcam.set({
    width: 400,
    height: 300,
    dest_width: 400,
    dest_height: 300,
    image_format: 'jpg',
    jpeg_quality: 100,
    flip_horiz: true
});

//Allow visitor to see the webcam.
Webcam.attach('#cameraView');

//Function to take a photo with the webcam.
function take_snapshot() {
    Webcam.snap(function (data_uri) {
        document.getElementById('cameraResult').innerHTML = '<img src="' + data_uri + '"/>';
        imageData = data_uri;
        var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
        document.getElementById('photoData').value = raw_image_data;
    });
}

//Hide the clear button by default.
$("#clearButton").hide();
$("#finishButton").hide();

//Toggle the availble button options.
$("#snapshotButton").click(function () {
    $("#cameraView").hide();
    $("#cameraResult").show();
    $("#snapshotButton").hide();
    $("#clearButton").show();
    $("#finishButton").show();
    $("#finishButton").prop("disabled", false);
});

$("#clearButton").click(function () {
    $("#cameraView").show();
    $("#cameraResult").hide();
    $("#snapshotButton").show();
    $("#clearButton").hide();
    $("#finishButton").hide();
    $("#finishButton").prop("disabled", true);
});

//Submit the photo to server on button press.
function sendPhoto() {
    document.getElementById('webcamForm').submit();
}