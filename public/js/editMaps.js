// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:

$("healthForm").submit(function(e){
  e.preventDefault();
});

function initMapForCreate() {
  var latitude = parseFloat(document.getElementById('latitude_txt').value);
  var longitude = parseFloat(document.getElementById('longitude_txt').value);
  var mapProp= {
    center: {lat: latitude, lng: longitude}, // Lahore Coordinates
    zoom: 18
  };

  var map = new google.maps.Map(document.getElementById('map'), mapProp);

  var inputAddress = document.getElementById('address_txt');
  var autocomplete = new google.maps.places.Autocomplete(inputAddress);
  
  // Bind the map's bounds (viewport) property to the autocomplete object,
  // so that the autocomplete requests use the current map bounds for the
  // bounds option in the request.
  autocomplete.bindTo('bounds', map);

  var infowindow = new google.maps.InfoWindow();
  var infowindowContent = document.getElementById('infowindow-content');
  infowindow.setContent(infowindowContent);

  var marker = new google.maps.Marker({
    map: map,          
    anchorPoint: new google.maps.Point(0, -29),
    position: {lat: latitude, lng: longitude},
    draggable:true
  });

  marker.addListener('dragend', function() {
    var lat = marker.getPosition().lat();
    var lng = marker.getPosition().lng();                 
    document.getElementById("latitude_txt").value = lat;
    document.getElementById("longitude_txt").value = lng;
  });

  map.addListener('idle', function() {
    var lat = marker.getPosition().lat();
    var lng = marker.getPosition().lng();                 
    document.getElementById("latitude_txt").value = lat;
    document.getElementById("longitude_txt").value = lng;
  });
      
  var place = autocomplete.getPlace();                              

  autocomplete.addListener('place_changed', function(event) {
    infowindow.close();
    marker.setVisible(false);
    var place = autocomplete.getPlace();
    // console.log(place.geometry);                             
    
    if (!place.geometry) {
      // User entered the name of a Place that was not suggested and
      // pressed the Enter key, or the Place Details request failed.
      document.getElementById("latitude_txt").value  = 0;
      document.getElementById("longitude_txt").value = 0;
      window.alert("No details available for address: '" + place.name + "'.\n Kindly select from options, THANK YOU");
      return ;       
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);  // Why 17? Because it looks good.
    }
    
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);
    console.log(place.address_components);

  });
}

$("#submitBtn").click(function() {
  $("#healthForm").submit(); // Submit the form
})