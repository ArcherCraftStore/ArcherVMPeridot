//Inspired by a dribbble by Bluroon
//https://dribbble.com/shots/1356864-Contact-Us

//Capture the click event on an location
$("#location-bar a").click(function(event){
  event.preventDefault();

  $(this).parent().toggleClass('active');

  var selectedMap = $(this).attr("href");
  var selectedLocation = $(this).data('location');
    //Update #map bkimage with the image from the location
    $('#map').css('background-image', 'url(' + selectedMap + ')');
    //update tooltip address
    $('.selectedLocation').text(selectedLocation);
});