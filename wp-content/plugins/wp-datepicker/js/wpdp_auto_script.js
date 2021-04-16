	
	

	jQuery(document).ready(function($){


		
		if($('.wpcf7-form-control.wpcf7-repeater-add').length>0){
			$('.wpcf7-form-control.wpcf7-repeater-add').on('click', function(){
				wpdp_refresh_1130(jQuery, true);
			});
		}
		
	
});
var wpdp_refresh_first_1130 = 'yes';
var wpdp_counter_1130 = 0;
var wpdp_month_array_1130 = [];
var wpdp_dateFormat = "mm/dd/yy";
var wpdp_defaultDate = "";
function wpdp_refresh_1130($, force){
				if(typeof $.datepicker!='undefined' && typeof $.datepicker.regional["en-GB"]!='undefined'){
					
				wpdp_month_array_1130 = $.datepicker.regional["en-GB"].monthNames;
									
				}
				
		
		
				


				if($(".datepickerdata").length>0){
					
				$(".datepickerdata").attr("autocomplete", "off");
					
				//document.title = wpdp_refresh_first=='yes';
				force = true;
				if(wpdp_refresh_first_1130 == 'yes' || force){
					
					if(typeof $.datepicker!='undefined')
					$(".datepickerdata").datepicker( "destroy" );
					
					$(".datepickerdata").removeClass("hasDatepicker");
					wpdp_refresh_first_1130 = 'done';
					
				}
				$('body').on('mouseover, mousemove', function(){//.datepickerdata									
				if ($(this).val()!= "") {
					$(this).attr('data-default-val', $(this).val());
				}		
							
				if(wpdp_counter_1130 > 2)
				clearInterval(wpdp_intv_1130);
				
				if(!$(".datepickerdata").hasClass('hasDatepicker')){

				
					
				$(".datepickerdata").datepicker($.extend(  
					{},  // empty object  
					$.datepicker.regional[ "en-GB" ],       // Dynamically  
					{  
 					dateFormat: wpdp_dateFormat
					}
				)).on( "change", function() {
						
				}); 
				
				
				
				
				
				$(".datepickerdata").datepicker( "option", "dateFormat", "mm/dd/yy" );


setTimeout(function(){ 

	 $.each($(".datepickerdata"), function(){

        
            $(this).prop('autocomplete', 'on');


         		 		
		var expected_default = $(this).data('default');		

		
		var expected_stamp = $(this).data('default_stamp');
		var expected_stamp_date = new Date(expected_stamp*1000);
		var expected_stamp_str = $.datepicker.formatDate('mm/dd/yy', expected_stamp_date);		 
	 
		if(expected_default != undefined && expected_default!=''){ $(this).datepicker().datepicker('setDate', expected_default); }
		if(expected_stamp != undefined && expected_stamp!=''){ $(this).datepicker().datepicker('setDate', expected_stamp_str); }		
		
	});
	
}, 100);
	






					$.each($(".datepickerdata"), function(){
						if($(this).data('default-val')!= ""){
							$(this).val($(this).data('default-val'));
						}
						
					});
						
				
				}
				});
				}
		


		
		$('.ui-datepicker').addClass('notranslate');
}
	var wpdp_intv_1130 = setInterval(function(){
		wpdp_counter_1130++;
		wpdp_refresh_1130(jQuery, false);
	}, 500);

	                jQuery(document).ready(function($){

                        $(".datepickerdata").on('click', function(){

                            $('.ui-datepicker-div-wrapper').prop('class', 'ui-datepicker-div-wrapper wp_datepicker_option-1 ');

                        });

                        setTimeout(function () {
                                $(".datepickerdata").click();
                                //$("//").focusout();
                        }, 1000);



                });

            
    //wpdp_refresh_//(jQuery, false);
	
	    
