$(function() {
  var componentForm = {
    street_number               : 'short_name',
    route                       : 'long_name',
    locality                    : 'long_name',
    administrative_area_level_1 : 'short_name',
    country                     : 'long_name',
    postal_code                 : 'short_name'
  };
  var acInputs = document.getElementsByClassName("autocomplete");
  for (var i = 0; i < acInputs.length; i++) {
    var autocomplete      = new google.maps.places.Autocomplete(acInputs[i]);
    autocomplete.inputId  = acInputs[i].id;
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
      var place    = this.getPlace();
      var aId      = this.inputId
      if (!place.geometry) {
        window.alert("No details available for input: '" + place.name + "'");
        return;
      }
      var latitude  = place.geometry.location.lat();
      var longitude = place.geometry.location.lng();
      $('.latitude'+aId).val(latitude);
      $('.longitude'+aId).val(longitude);
      //console.log(place);
      // Get each component of the address from the place details
      // and fill the corresponding field on the form.
      for (var j = 0; j < place.address_components.length; j++) {
        var addressType = place.address_components[j].types[0];
        if (componentForm[addressType]) {
          var val = place.address_components[j][componentForm[addressType]];
          $('.'+addressType+aId).val(val);
        }
      }
    });
  }
});