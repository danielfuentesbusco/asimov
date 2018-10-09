@extends('layouts.app')

@section('content')



 <!-- Modal -->
 <div class="container">
	 <input id="fecha" type="date" name="fecha" value="" /><input id="btn-submit" type="button" name="" value="Search" /><br /><br />
	 <div class="alert alert-danger fade out" style="display: none;">
		  <a href="#" class="close" aria-label="close">&times;</a>
		  <strong id="mensaje"></strong>
		</div>

  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="    height: 0px;">
    
      <!-- Modal content-->
      <div class="modal-content">
	      <form id="apointment_form">
        <div class="modal-header">
          <h4 class="modal-title">Create appointment</h4>
        </div>
        <div class="modal-body">
          <p>Date: <input type="text" name="date" id="date" readonly="readonly" /></p>
          <p>Hour: <input type="text" name="hour" id="hour" readonly="readonly" /></p>
          <p>Email: <input type="email" name="contact" id="contact" required="required" /></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-save">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
	      </form>
      </div>
      
    </div>
  </div>
  
  
  <div class="mycal"></div>
  </div>
  

<script>	
$(document).ready(function() {
	var eventos = function(start, end){
		console.log(start, end);
		$.get( "/api/appointments", function( data ) {
		  	$('.mycal').easycal({
				columnDateFormat : 'DD-MM-YYYY',
				timeFormat : 'hh A',
				minTime : '09:00:00',
				maxTime : '18:00:00',
				slotDuration : 60,
				/*dayClick : null,*/
				/*eventClick : null,*/
				startDate : start, 
				startDate : end, 
				events :  data,
				widgetHeaderClass : 'ec-day-header',
				widgetSlotClass : 'ec-slot',
				widgetTimeClass : 'ec-time'
			});
			$(".ec-slot").unbind("click");
			$(".ec-slot").click(function(){
				$('#hour').val($(this).attr("data-time"));
				$('#date').val($(this).closest(".ec-slot-col").attr("data-date"));
				$('#myModal').modal('show');
			});
			
			$(".close").unbind("click");
			$(".close").click(function(){
				$(".alert").hide(); 
			});
			
			$(".btn-save").unbind("click");
			$(".btn-save").click(function(){
				var date = $('#date').val();
				var hour = $('#hour').val();
				var contact = $('#email').val();
				$.ajax({
				  type: "POST",
				  url: "/api/appointments",
				  data: $( "#apointment_form" ).serialize(),
				  success: function( data ) {
					  var obj = JSON.parse(data.responseText);
					  $('#myModal').modal('hide');
					  $(".alert").show(); 
					  $("#mensaje").text(obj.mensaje );
					  
					  // Se pinta box de cita
					  
					},
					error: function( data ) {
						var obj = JSON.parse(data.responseText);
					  console.log( obj.mensaje );
					  $('#myModal').modal('hide');
					  $(".alert").show(); 
					  $("#mensaje").text(obj.mensaje );
					},
				  dataType: 'jsonp'
				});
			});
		});	
	}
	
	eventos(Date.today().last().monday().toString("dd-MM-yyyy"), Date.today().next().sunday().toString("dd-MM-yyyy"));
	
	$("#btn-submit").click(function(){
		var val = $("#fecha").val();
		console.log(val);
		eventos(Date.parse(val).last().monday().toString("dd-MM-yyyy"), Date.parse(val).next().sunday().toString("dd-MM-yyyy"));
	});		
});
</script>
@endsection
