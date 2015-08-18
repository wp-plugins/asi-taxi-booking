<?php

add_action('init', 'asi_booktaxi_register_shortcodes');

function asi_booktaxi_register_shortcodes() {
 

    add_shortcode('asi-booktaxi', 'asi_taxi_shortcode');

}
function asi_taxi_shortcode($atts) {

         $carinfo=new asi_taxibook_plugin_admin();
         $allfare=$carinfo->taxi_allselected_fare();
         $cartypes=$carinfo->taxi_allselected_car();         
         $select='<select name="cartypes" id="cartypes" style="width:120px;height: 29px;padding-left: 10px; margin-left: 48px;">';
         $select.='<option value="select" >Select Taxi </option>';
         foreach($cartypes as $car)
         {
            $select.='<option value="'.$car['fare'].'">'.$car['name'].'</option>';
         }
         $select.='</select>';
         $color=$allfare[0]['color'];
         if($color!="")
         {

            $color='style="background-color:'.$allfare[0]['color'].'"';

         }        
		$displayform='<form id="order"><div class="taxi_table col-md-6"'.$color.'><table id="customer_order" style="margin: 0 0 2px;" class="table-float">

	<tbody>
   <tr>
	<td style="padding-top: 10px"> 
    <input style="height: 29px; padding-left: 15px; width: 172px;" type="date" id="bdate" min="2015-06-01"></td><td>
    <input style="width: 120px; margin-left:48px; height:29px;padding-left: 8px;" type="time" id="btime" value="16:00" name="usr_time"></td></tr>
    <tr style="    border-bottom: solid 1px rgba(16, 69, 128, 0);"><td width="170px" valign="middle" style="padding-bottom: 5px">
    <div> <strong>Taxi Type</strong></div></td><td valign="middle"  style="padding-bottom: 5px;">'.$select.'</td></tr>
    <tr>
	<td colspan="2" style="padding-top: 10px"> 
    <input style="border: 1px solid #104580;" id="source" name="source" type="textbox" placeholder="PickUp Address" value="" class="addressBox" />
            </td></tr>           
		<tr>
			<td style="padding-bottom: 5px; width: 170px;"><strong>Additional Stops:</strong> 
			<div id="stops_div" ></div></td><td><input style="padding: 0px;padding-left: 15px;    width: 120px; margin-left: 48px; height: 29px;" type="number" value="0" min="0" class="mystop" name="stops_count" id="stops_count"  style="vertical-align: middle" /></td></tr>
            <tr>
        	<td colspan="2" style="border-bottom: solid 1px rgba(16, 69, 128, 0);">
            <input style="border: 1px solid #104580;" type="textbox" id="destination"  name="destination"  placeholder="DropOff Address"  class="addressBox" value=""/>
          </td>
		</tr>
        <tr style="border-bottom: solid 1px rgba(16, 69, 128, 0);line-height:5.0px;">
		<td style="padding-top: 10px; padding-bottom: 1px; width: 170px;border-top: none;">
        	<strong style="padding-right: 37px; padding-left: 15px;">Adults</strong><strong>Infants</strong></td>
        <td style="padding-top: 10px; padding-bottom: 1px; width: 170px;border-top: none;">
        	<strong style="padding-right:5px;padding-left:5px;">Baby Seats</strong><strong style="padding-left:25px;">Bags</strong></td>

		</tr>
        <tr style="    border-bottom: solid 1px rgba(16, 69, 128, 0);">   
        <td  style=" padding-bottom:1px; padding-left: 14px;border-top: none;">  
        <input  style="padding: 0px;padding-left: 15px;" type="number" value="0" min="0" max="10" class="mystop" name="adult_seat" id="adult_seat">
        <input  style="margin-left: 18px; padding: 0px;padding-left: 15px;" type="number" value="0" min="0" max="10" class="mystop" name="enf_seat" id="enf_seat">
        </td>
        <td style="padding-top: 1px; padding-bottom: 1px;border-top: none;">
        <input  style="padding: 0px; padding-left: 15px;margin-left: 9px;" type="number" value="0" min="0" max="10" class="mystop" name="baby_seat" id="baby_seat">
         <input style=" margin-left: 15px; padding: 0px;padding-left:15px;" type="number" value="0" min="0" max="10" class="mystop" name="lugg" id="lugg">
        </td>
        </tr>
         <tr>
	<td colspan="2" style="padding-top: 5px"> 
    <input style="border: 1px solid #104580;" id="bname" name="bname" type="textbox" placeholder="Name" class="addressBox" />
            </td></tr> 
        <tr>
	<td colspan="2" style="padding-top: 0px"> 
    <input style="border: 1px solid #104580;" id="bemail" name="bemail" type="text" placeholder="Your Email" class="addressBox" />
            </td></tr>
    <tr>
	<td colspan="2" style="padding-top: 0px"> 
    <input style="border: 1px solid #104580;" id="bcell" name="bcell" type="textbox" placeholder="Your Phone Number" class="addressBox" />
            </td></tr> 
  	<tr><td colspan="2">
                <input type="hidden" name="distance"  id="distance" readonly value=""/>
                <input type="hidden" name="fare" id="fare" readonly value=""/>
                <input type="hidden" name="duration" id="duration" readonly value=""/>
            </td></tr>
  <tr>
    <td colspan="2" align="center" valign="bottom" style="padding-top: 12px;text-align: center;">
      <input type="button" id="cal1" name="submit" value="Book" onClick="doCalculation()"/>
      <input type="button" id="res1" name="reset" value="Reset" style="margin-left: 10px;"  onclick="clear_form_elements(this.form)"/>
    </td>
  </tr>
       <input type="hidden"  name="stopfare" id="stopfare" value="'.$allfare[0]['stop'].'"/>
                <input type="hidden"  name="milefare" id="milefare" value="'.$allfare[0]['mile'].'"/>
                <input type="hidden"  name="seatfare" id="seatfare" value="'.$allfare[0]['seat'].'"/>
                <input type="hidden"  name="minutefare" id="minutefare" value="'.$allfare[0]['minute'].'"/>
                <input type="hidden"  name="currfare" id="currfare" value="'.$allfare[0]['curr'].'"/>
                <input type="hidden"  name="adulfare" id="adulfare" value="'.$allfare[0]['adul'].'"/>
                <input type="hidden"  name="inffare" id="inffare" value="'.$allfare[0]['inf'].'"/>
                <input type="hidden"  name="luggfare" id="luggfare" value="'.$allfare[0]['lugg'].'"/>
</tbody></table>
	<div class="table-float" style="text-align: center">
		<div id="po" style="display: none; text-align: left"></div> 
	</div>
	<div class="clear"></div>
</div>
<div class="clear"></div>
</form>';
return $displayform;

} 

?>