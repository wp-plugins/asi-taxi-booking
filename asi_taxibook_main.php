<?php

add_action('init', 'asi_booktaxi_register_shortcodes');
function asi_booktaxi_register_shortcodes() {
    add_shortcode('asi-booktaxi', 'asi_taxi_shortcode');
}
function asi_taxi_shortcode($atts) {
      $carinfo=new asi_taxibook_plugin_admin();

         $allfare=$carinfo->taxi_allselected_fare();

         $cartypes=$carinfo->taxi_allselected_car();         

         $select='<select name="cartypes" class="form-control" id="cartypes" style="width: 75%;padding-left: 15px; float: right;">

								';

         $select.='<option value="select" > Select Taxi </option>';

         foreach($cartypes as $car)

         {

            $select.='<option value="'.$car['fare'].'">'.$car['name'].'</option>';

         }

         $select.='</select>';

         $color=$allfare[0]['color'];

         if($color!="")

         {



            $color='background-color:'.$allfare[0]['color'];



         }        

		$displayform='<div class="container">

			<div class="row">

				<div class="col-lg-5 col-md-6 col-sm-7 col-xs-12" id="main1" style="'.$color.'; padding-bottom: 15px">

					<form id="order" method="">

						<div class="row" style="padding-top: 15px;">

							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

								<input style="padding-left: 15px; width: 100%;" class="form-control" type="date" id="bdate" min="2015-06-01">

							</div>

							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

								<input style="width: 75%; float: right;padding-left: 15px;" class="form-control" type="time" id="btime" value="16:00" name="usr_time">

							</div>

						</div>

						<div class="row">

							<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 15px">

								Taxi Type

							</label>

							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 15px;">

								'.$select.'

							</div>

						</div>

						<div class="row">

							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;">

								<input type="text" class="form-control" id="source" name="source" placeholder="PickUp Address">                                

								<input style="display: none;" type="text" hidden class="form-control" id="stops_count_s" name="stops_count">

							</div>

						</div>

						<div class="row">

							<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 15px">

								Additional Stops

							</label>

							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 15px;">     

								<input style="padding-left: 15px; width: 75%; float: right;" class="form-control" type="number" value="0" min="0" name="stops_count" id="stops_count">

							</div>

						</div>

						<div class="row">

							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;">

                            <input type="textbox" id="destination" name="destination" placeholder="DropOff Address" class="form-control" value="" />

							</div>

						</div>

						<div class="row" >

							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

								<label class="" style="padding-top: 14px">

									Adults

								</label>

                             <input type="number" value="0" min="0" max="10" class="form-control" name="adult_seat" id="adult_seat" value="" />

							</div>

							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

								<label class="" style="padding-top: 14px">

									Infants

								</label>

                                <input type="number" value="0" min="0" max="10" class="form-control" value="" name="enf_seat" id="enf_seat" />

							</div>

							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

								<label class="" style="padding-top: 14px">

									BabySeats

								</label>

                                <input type="number" value="0" min="0" max="10" class="form-control" value="" name="baby_seat" id="baby_seat" />

							</div>

							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

								<label class="" style="padding-top: 14px">

									Bags

								</label>

                               <input type="number" value="0" min="0" max="10" class="form-control" value="" name="lugg" id="lugg" />

							</div>

						</div>

						<div class="row">

							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;">

                            <input type="textbox" id="bname" name="bname" placeholder="Your Name" class="form-control" value="" />

							</div>

							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;">

                            <input type="textbox" id="bemail" name="bemail" placeholder="Your Email" class="form-control" value="" />

							</div>

							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;">

                             <input type="textbox" id="bcell" name="bcell" placeholder="Your Phone No" class="form-control" value="" />

							</div>

						</div>

						<div class="calBlue_line">

						</div>

						<div class="form-group">

							<div class="col-xs-12" style="text-align: center;padding-top: 15px; margin-bottom: 15px">

								<input type="button" id="cal1" value="Book" onClick="doCalculation()" class="btn btn-primary " name="submit" style="font-size: 14px; font-weight: bold" />

								<input type="button" id="res1" class="btn" name="reset" value="Reset" onclick="clear_form_elements(this.form)" style="font-size: 14px; font-weight: bold;" />

							</div>

                            <input type="hidden" name="distance"  id="distance" readonly value=""/>

                <input type="hidden" name="fare" id="fare" readonly value=""/>

                <input type="hidden" name="duration" id="duration" readonly value=""/>

                <input type="hidden"  name="stopfare" id="stopfare" value="'.$allfare[0]['stop'].'"/>

                <input type="hidden"  name="milefare" id="milefare" value="'.$allfare[0]['mile'].'"/>

                <input type="hidden"  name="seatfare" id="seatfare" value="'.$allfare[0]['seat'].'"/>

                <input type="hidden"  name="minutefare" id="minutefare" value="'.$allfare[0]['minute'].'"/>

                <input type="hidden"  name="currfare" id="currfare" value="'.$allfare[0]['curr'].'"/>

                <input type="hidden"  name="adulfare" id="adulfare" value="'.$allfare[0]['adul'].'"/>

                <input type="hidden"  name="inffare" id="inffare" value="'.$allfare[0]['inf'].'"/>

                <input type="hidden"  name="luggfare" id="luggfare" value="'.$allfare[0]['lugg'].'"/>             

			

						</div>

						<div class="table-float" style="text-align: center; margin-top: 10px; float: none">

							<div id="po" style="display: inline-block; text-align: left">

							</div>

						</div>

					</form>

				</div>

			</div>

		</div>

        <div class="table-float" style="text-align: center">

		<div id="po" style="display: none; text-align: left"></div> 

	</div>';

return $displayform;



} 



?>