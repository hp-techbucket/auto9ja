			
		
		/*
		**	DATATABLE FUNCTION
		**  DISPLAY VEHICLES
		**  WITH SEARCH AND PAGINATION
		*/ 
		
		$(document).ready(function() {
		
			//vehicles-listings-table
			var table = $('#vehicles-table').DataTable({ 
		 
				"language": {
				   "emptyTable": "<div class=\"alert alert-default\"><i class=\"fa fa-ban\"></i> No records!</div>", // 
				   "loadingRecords": "Please wait...", // default Loading...
				   "zeroRecords": "No matching records found!"
				},
				
				"processing": true, //Feature control the processing indicator.
				//"serverSide": true, //Feature control DataTables' server-side processing mode.
				"order": [], //Initial no order.
				
				//Set column definition initialisation properties.
				"columnDefs": [
				{ 
					"targets": [ 0 ], //first column / numbering column
					"orderable": false, //set not orderable
					//"className": 'mdl-data-table__cell--non-numeric', //Material Design
				},
				],
				//"sDom": 'T<"clear">lfrtip<"clear spacer">T',
				"dom":' <"search"f><"top"l>rt<"bottom"ip><"clear">',
				
				responsive: true
				
				
			});
			
			
		
			
	});
	

  