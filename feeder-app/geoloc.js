var feeder_url = 'http://www.domain.com/pathto/service.php';

var timerupdate = null;
var jQT = new $.jQTouch({
                icon: './geoloc.png',
				addGlossToIcon: true,
                statusBar: 'black',
				fullScreen: true,
				startupScreen: 'startup.png',
				useFastTouch: false,
            });

            // Some sample Javascript functions:
            $(function(){
				$('body').ready(function() {
					updateLocation();
					//timerupdate = setInterval("updateLocation()",5000);
				});
				$('#status').change(function() {
					updateLocation();
					s = $("select#status option:selected").val();
					switch(s) {
						case "0":
						case "2":
						case "3":
							if (timerupdate!=null) clearInterval(timerupdate);
							break;
						case "1":
							timerupdate = setInterval("updateLocation()",5000);
							break;
					}
				});
            });

function updateLocation() {
	if ( !$('#updatestatus').hasClass('loading')) {
		$("#updatestatus").addClass('loading');
		navigator.geolocation.getCurrentPosition(foundLocation);
	}
}

function send_json(text) {
	if(navigator.onLine) {
		$.ajax({
			cache: false,
			type:'POST',
			async:true,
			url:feeder_url,
			data: { data:text },
			timeout: 10000,
			error:	function(o,msg,e){
						$("#server_updates").html(msg);
						$("#updatestatus").removeClass('loading');
					},
			success: function(msg) {
						$("#server_updates").html(msg);
						$("#updatestatus").removeClass('loading');
					},
		});
	} else {
		$("#server_updates").html("<p class='error'>No network</p>");
	}
}

function foundLocation(position)
{
  var latitude = position.coords.latitude;
  var longitude = position.coords.longitude;
  var timestamp = position.timestamp;
  var accuracy = position.coords.accuracy;
  var lastupdate = new Date();
  var data = new Object();

  lastupdate.setTime(timestamp);

  var year		= lastupdate.getUTCFullYear();
  var month		= lastupdate.getUTCMonth();
  var day		= lastupdate.getUTCDate();
  var hours		= lastupdate.getUTCHours();
  var minutes	= lastupdate.getUTCMinutes();
  var seconds	= lastupdate.getUTCSeconds();


  $("#latitude_val").html(latitude);
  $("#longitude_val").html(longitude);
  $("#accuracy_val").html(accuracy);
  $("#lastupdate_val").html(lastupdate.toLocaleString());

  data.latitude=latitude;
  data.longitude=longitude;
  data.accuracy=accuracy;
  //data.timestamp= "" + year + month + day + hours + minutes + seconds ;
  data.timestamp= (timestamp / 1000);
  data.status=$("select#status option:selected").val();
  var dataString = $.toJSON(data);

  send_json(dataString);
//  $("#status").toggleClass('statusloading',false);
}

function noLocation()
{
  $("#latitude_val").html("N/A");
  $("#longitude_val").html("N/A");
  $("#lastupdate_val").html("N/A");
//  $("#status").toggleClass('statusloading',false);
	$("#updatestatus").removeClass('loading');
}

