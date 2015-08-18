/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//var taxi_type;
var stops_count = 0;
var baby_count = 0;
var markerBounds = new google.maps.LatLngBounds();
var geocoder = new google.maps.Geocoder();
var markers = new Array();
var directionsDisplay;
var directionsService;
var map;
var lats = '';
var lngs = '';
count_markers = 0;
var waypoints = new Array();
var first_time = true;
jQuery(document).ready(function()
{
    // code for google auto suggestion address for pick up location
    var input = document.getElementById('source');
    var autocomplete = new google.maps.places.Autocomplete(input);

    // code for Google auto suggestion address for destination location
    var drop = document.getElementById('destination');
    var drop_autocomplete = new google.maps.places.Autocomplete(drop);
  
});

function doCalculation()
{
      
    var pattern = /\S+@\S+\.\S+/;                 
    var cartype=document.getElementById('cartypes').value;
    var address = document.getElementById('source').value;
    var destination = document.getElementById('destination').value;
    var date = document.getElementById("bdate").value;
    var name = document.getElementById("bname").value;
    var email =document.getElementById("bemail").value;
    var cell =document.getElementById("bcell").value;
    
    if(cartype.trim()=='' || cartype.trim()=='select')
    {
        alert("Please Select Car Type.");
        return false;
    }
    if(address.trim() == '') {
        alert("Please Enter Pickup Address");
        source = '';
        return false;
    }
    if(date.trim() == '') {
        alert("Please Select Date and Time");
        date = '';
        return false;
    }
    if(destination.trim() == '') {
        alert("Please Enter Destination Address");
        destination = '';
        return false;
    }
    if(name.trim() == '') {
        alert("Please Type Your Name:");
        name = '';
        return false;
    }
    if(email.trim() == '') {
        alert("Please Type Your Email Address:");
        email = '';
        return false;
    }
    if(cell.trim() == '') {
        alert("Please Type Your Cell Number:");
        cell = '';
        return false;
    }
    if (!pattern.test(email)) {
           alert('Provide valid Email Address');
           $('#bemail').focus();
           return false;
    }
    else
    {
       calcfare();
    }

}

function calcfare() {
    var source = document.getElementById("source").value;
    var stops_count = document.getElementById("stops_count").value;
    var destination = document.getElementById("destination").value;
    var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix(
            {
                origins: [source],
                destinations: [destination],
                travelMode: google.maps.TravelMode.DRIVING,
                avoidHighways: false,
                avoidTolls: false
            }, callback);

    function callback(response, status) {
        console.log(status);
        if (status == google.maps.DistanceMatrixStatus.OK) {
            var origins = response.originAddresses;
            var destinations = response.destinationAddresses;

            for (var i = 0; i < origins.length; i++) {
                var results = response.rows[i].elements;
                for (var j = 0; j < results.length; j++) {
                    var element = results[j];
                    if (element.status == "NOT_FOUND") {
                        document.getElementById("source").value = '';
                        alert("Please enter valid pickup address.");
                        return 0;
                    }
                    if (element.status == "ZERO_RESULTS") {
                        document.getElementById("destination").value = '';
                        document.getElementById("source").value = '';
                        alert("Please enter valid addresses.");
                        return 0;
                    }
                    var distance = element.distance.text;
                    var duration = element.duration.text;
                    console.log("dist:: " + distance + "\n dura:: " + duration);
                    document.getElementById("duration").value = duration;
                    document.getElementById("distance").value = distance;

                    var m_distance = 0.00, mile_distance = 0.00, ft_distance = 0.00, km_distance = 0.00, estimated_fare;
                    var distance_array = distance.split(" ");

                    distance_array[0] = distance_array[0].replace(/\,/g, ''); // 1125, but a string, so convert it to number
                    distance_array[0] = parseFloat(distance_array[0]);
                    if (distance_array[1] == 'm') {
                        m_distance = distance_array[0] / 1000;
                        mile_distance = parseFloat(m_distance) / 1.6;
                    } else if (distance_array[1] == 'ft') {
                        ft_distance = distance_array[0];
                        mile_distance = parseFloat(ft_distance) / 5280;
                    } else if (distance_array[1] == 'km') {
                        km_distance = parseFloat(distance_array[0]);
                        mile_distance = parseFloat(km_distance) / 1.6;
                    } else if (distance_array[1] == 'mi') {
                        mile_distance = distance_array[0];
                    }
                    dur_mins = 0;
                    var dur_array = duration.split(" ");
                    if (dur_array.length == 2) {
                        if (dur_array[1] == "mins") {
                            dur_mins = dur_array[0];
                        } else if (dur_array[1] == "hours" || dur_array[1] == "hour") {
                            dur_mins = parseFloat(dur_array[0]) * 60;
                        }
                    } else if (dur_array.length == 4) {
                        dur_mins = parseFloat(dur_array[2]);
                        dur_mins = dur_mins + parseFloat(dur_array[0]) * 60;
                    }
                    console.log("miles: " + mile_distance);
                    console.log("mins: " + dur_mins);
                    //............
                    var date = document.getElementById("bdate").value;
                    var time = document.getElementById("btime").value;
                    var datetime=date+' '+time;
                    var cartype=document.getElementById('cartypes').value;
                    var carname=jQuery("#cartypes option:selected").text();
                    var lugg = document.getElementById('lugg').value;
                    baby_count = parseFloat(document.getElementById("baby_seat").value);
                    var name = document.getElementById("bname").value;
                    var email =document.getElementById("bemail").value;
                    var cell =document.getElementById("bcell").value;
                    var adul = document.getElementById('adult_seat').value;
                    var inf = document.getElementById('enf_seat').value;
                    var lugg = document.getElementById('lugg').value;
                    
                    var minutefare =document.getElementById('minutefare').value;
                    var stopfare=document.getElementById('stopfare').value;
                    var milefare=document.getElementById('milefare').value;
                    var seatfare=document.getElementById('seatfare').value;
                    var curr=document.getElementById('currfare').value;
                    var adulfare=document.getElementById('adulfare').value;
                    var inffare=document.getElementById('inffare').value;
                    var luggfare=document.getElementById('luggfare').value;
                    
                    var cartype=parseInt(cartype);
                   estimated_fare = cartype  + (mile_distance * milefare) + (dur_mins * minutefare) + (baby_count * seatfare) + (stops_count * stopfare)+ (adul * adulfare)+(inf * inffare)+(lugg * luggfare);
                   //alert(estimated_fare);
                   estimated_fare = curr + estimated_fare.toFixed(2);
                    if (mile_distance < .2) {
                        mile_distance = mile_distance * 5280;
                        mile_distance = mile_distance.toFixed(2) + ' Feet';
                    } else {
                        // estimated_fare
                        mile_distance = mile_distance.toFixed(2) + ' Miles';
                    }
                    document.getElementById("distance").value = mile_distance;
                    document.getElementById("fare").value = estimated_fare;
                    document.getElementById("duration").value = duration;
                     dsp();
                    document.getElementById("po").innerHTML = "<span class='nearest'> Estimated Fare : " + jQuery("#fare").val() + "</span><br>";
                    jQuery("#po").append("<span class='nearest'>Distance :" + jQuery("#distance").val() + "</span><br>");
                    jQuery("#po").append("<span class='nearest'>Duration :" + jQuery("#duration").val() + "</span><br>");
                    
                    var r = confirm("Do you want to book a taxi?");
                    if (r == true) {
                        jQuery.post(document.location.protocol+'//'+document.location.host+'/wp-admin/admin-ajax.php', 
                        {action: 'asi_taxibooking', 
                            name : name,email : email,cell:cell,pick:source,drop:destination,
                            date : datetime
                            },function(data){
                            alert('An Email has been sent to Admin!');
                                });
                                return false;
                    } else {
                        return false;
                    }
                }
            }
        }
    }
}

function dsp() {
    if (jQuery("#po").is(":hidden")) {
    }
    else {
        jQuery("#po").hide();
    }
    jQuery("#po").slideDown("slow", function() {
    });
}

var s = "";
var count = 0;
var id = 0;

function clear_form_elements(ele) {
    count=0;
    first_time = true;
    jQuery("#po").html("");
    jQuery("#po").css("display","none");
    jQuery("#map_canvas").hide();

    tags = ele.getElementsByTagName('input');
    for (i = 0; i < tags.length; i++) {
        switch (tags[i].type) {
            case 'password':
            case 'text':
                tags[i].value = '';
                break;
            case 'checkbox':
            case 'radio':
                tags[i].checked = false;
                break;
        }
    }
    document.getElementById("baby_count").value = 0;
    document.getElementById("stops_div").value = "";
    document.getElementById("stops_count").value = 0;


}
jQuery(document).on('click','.rem',function(){
   var id = jQuery(this).attr('content'); 
    //............
    var h1=window.location.pathname;
    var base_url= window.location.origin+h1; 
    base_url=base_url.replace('/wp-admin/admin.php','');
 jQuery.post(base_url+'/wp-admin/admin-ajax.php', {action: 'asi_deletetaxi', id:id},function(data){
    //alert(data);
    	         }); 
});
