<?
include_once("../connect.php");
$t_map = $_GET["t_map"];
$t_lat = $_GET["t_lat"];
$t_lng = $_GET["t_lng"];
$t_desc = $_GET["t_desc"];

if($t_lat=="") {
  $t_lat = "13.883819398067704";
}
if($t_lng=="") {
  $t_lng = "100.48713315462105";
}
?>

<div class="modal-content">

    <div class="modal-body">
      <div class="row">
        <div class="col-xs-12">
          <strong class="menu_n_menu">Map Setting</strong>
        </div>
      </div>
      <br>

      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label class="col-lg-12 control-label">Map Title</label>
            <div class="col-lg-12">
              <input type="text" class="form-control" id="map_title" value="<?=$t_map?>">
            </div>
          </div>
        </div>
      </div>
      <br>

      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <div class="col-lg-12">
              <input id="pac-input" class="form-control" type="text"  placeholder="Search Box"/>
              <div class="maparea" id="map"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label class="col-lg-12 control-label latlng">You are here ( Lat : <?=$t_lat?> Lng : <?=$t_lng?> )</label>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label class="col-lg-12 control-label">Map Description</label>
            <div class="col-lg-12">
              <textarea id="my_mapdesc" class="form-control" rows="3"><?=$t_desc?></textarea>
            </div>
          </div>
        </div>
      </div>


      <br>
      <div class="row">
          <div class="col-xs-12">
            <button type="button" onclick="saveNewMapdata();" class="btn btn-success" style="width:150px;">Save</button>
              &nbsp;&nbsp;&nbsp;
            <a onclick="closeMap();" class="btn btn-default" style="width:150px;">Cancel</a>
          </div>
      </div>


    </div>

</div>

<input type="hidden" id="mLat" value="<?=$t_lat?>">
<input type="hidden" id="mLng" value="<?=$t_lng?>">

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfB9v8-IazC6JveT2VqMWPrP024evjATQ&callback=initMap&libraries=places&v=weekly" defer></script>
<script type="text/javascript">
var marker;
var infowindow = "";
function initMap() {

  const uluru = { lat: <?=$t_lat?>, lng: <?=$t_lng?> };
  const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: <?=$t_lat?>, lng: <?=$t_lng?> },
    zoom: 13,
    mapTypeId: "roadmap",
  });

  marker = new google.maps.Marker({
    position: uluru,
    map: map,
    draggable:true
  });
  geocodePosition(marker.getPosition());


  const input = document.getElementById("pac-input");
  const searchBox = new google.maps.places.SearchBox(input);

  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  map.addListener("bounds_changed", () => {
    searchBox.setBounds(map.getBounds());
  });

  searchBox.addListener("places_changed", () => {
    const places = searchBox.getPlaces();
    if (places.length == 0) {
      return;
    }
    const bounds = new google.maps.LatLngBounds();
    places.forEach((place) => {
      if (!place.geometry || !place.geometry.location) {
        console.log("Returned place contains no geometry");
        return;
      }

      marker.setPosition(place.geometry.location);
      geocodePosition(marker.getPosition());

      if (place.geometry.viewport) {
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }

    });
    map.fitBounds(bounds);
  });


  google.maps.event.addListener(map, 'click', function(event) {
     marker.setPosition(event.latLng);
     geocodePosition(marker.getPosition());
  });

  google.maps.event.addListener(marker, 'dragend', function()
  {
      geocodePosition(marker.getPosition());
  });

  function geocodePosition(pos)
  {
    $('.latlng').html('You are here ( Lat : '+marker.getPosition().lat()+' Lng : '+marker.getPosition().lng()+' )');
    $('#mLat').val(marker.getPosition().lat());
    $('#mLng').val(marker.getPosition().lng());
     geocoder = new google.maps.Geocoder();
     geocoder.geocode
      ({
          latLng: pos
      },
          function(results, status)
          {
              if (status == google.maps.GeocoderStatus.OK)
              {
                if(infowindow=="") {

                } else {
                  infowindow.close();
                }
                 infowindow = new google.maps.InfoWindow({
                    content: results[0].formatted_address
                 });
                 infowindow.open(map,marker);
                 //$('#my_mapdesc').val(results[0].formatted_address);
              }
              else
              {
                  //$('#my_mapdesc').val('');
                  $('.latlng').html('You are here ( Lat : Lng : )');
              }
          }
      );
  }
}
window.initMap = initMap;


function saveNewMapdata() {
  $('#maptitle').val($('#map_title').val());
  $('#maplat').val($('#mLat').val());
  $('#maplng').val($('#mLng').val());
  $('#mapdesc').val($('#my_mapdesc').val());
  closeMap();
}
</script>
