<?php
global $Itemid , $xhtml ,$full_message;
include(JPATH_SITE.DS.'components'.DS.'com_dtregister'.DS.'views'.DS.'event'.DS.'tmpl'.DS.'event_header.php');
?>
<div>
  <?php
      $tEvent = $this->tEvent ;
       $registered = $tEvent->getTotalregistered($tEvent->slabId);
      $message =  ($registered >= $tEvent->max_registrations && $tEvent->max_registrations !=0 &&$tEvent->max_registrations!="")?$full_message:$this->tEvent->topmsg;
	  
	  echo ($message!="")?$message."<br /><br />":'';
  ?>
</div>
<div id="price_header">
<?php
 //include(JPATH_SITE.DS.'components'.DS.'com_dtregister'.DS.'views'.DS.'event'.DS.'tmpl'.DS.'price_header.php');
?>
</div>
<form name="frmcart" method="post" action="index.php?option=com_dtregister&Itemid=<?php echo $Itemid ?>" enctype="multipart/form-data">
   <table>
     <?php if($this->tEvent->use_discountcode){?>
      <tr>
         <td>
           <?php echo JText::_( 'DT_DISCOUNT_CODE'); ?>
         </td>
         <td>
           <input type="text" name="discount_code" id="discount_code" value="" class="inputbox"  /><label for="discount_code" style="display:none" generated="true" class="error" ></label>
         </td>
      </tr>
      <?php } ?>
     <?php
       
	   echo  $this->form ;
	   
	   echo  $this->userFields($this->tEvent->usercreation);
	   echo $this->capthaField();
	   echo $this->termsField($this->eventId);
	   
	 ?>
     
     <tr>
     <td>
         <input type="button" class="button" onclick="window.location='<?php echo $this->back; ?>'" value="<?php echo JText::_('DT_BACK'); ?>" id="next" name="billingInfo"/>
       <input type="submit" name="confirm" class="button" id="next" value="<?php echo JText::_( 'DT_NEXT_BUTTON' ); ?>"  />
     </td>
     
     </tr>
   </table>
   
   <input type="hidden" name="option" value="com_dtregister" />
   <input type="hidden" name="controller" value="event" />
   <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
   <input type="hidden" name="task" value="<?php echo  $this->task; ?>" />
   
</form>
<div id="debug">
</div>
<script type="text/javascript">
 
  
 var updateFee =  function(){
	    
	   var prevtask = DTjQuery(document.frmcart.task).val();
	   var options = {
		               url : "<?php echo JRoute::_("index.php?no_html=1&tmpl=price_header");?>",
					   success : function(responseText){
						             DTjQuery("#price_header").html(responseText);
					              }
		   }
	   DTjQuery(document.frmcart.task).val('price_header');
	   DTjQuery(document.frmcart).ajaxSubmit(options);
	   DTjQuery(document.frmcart.task).val(prevtask);
	     
  }
  
  updateFee();
 
 DTjQuery(function(){
	  DTjQuery('#next').live('click',function(){
	     
		 
		 if(DTjQuery(document.frmcart).valid()){
		   DTjQuery('select').removeAttr('disabled');
		   DTjQuery('input').removeAttr('disabled');
		   DTjQuery('textarea').removeAttr('disabled');
		   
		 }
	  });

    DTjQuery.validator.messages.required = " ";
    DTjQuery.validator.messages.remote = " ";
    DTjQuery(document.frmcart).validate({
	        success: function(label) {
				label.addClass("success");
			}

	});
	<?php if($this->tEvent->use_discountcode){?>
	 DTjQuery('#discount_code').rules('add',
	  {
		  remote:{ 
		     url :"<?php echo JRoute::_('index.php?option=com_dtregister&controller=validate&task=discountcode&no_html=1',$xhtml); ?>",
			 dataType:'json',
			 success:function(response){
			var previous = {};
			var element = DTjQuery('#discount_code')[0];
			var validator = DTjQuery(document.frmcart).data().validator ;
			//validator.settings.messages[element.name].remote = DTjQuery('#discount_code').data().previous.originalMessage;

						var valid = response === true;

						if ( valid ) {

							var submitted = validator.formSubmitted;

							validator.prepareElement(element);

							validator.formSubmitted = submitted;

							validator.successList.push(element);

							validator.showErrors();
							 updateFee();

						} else {

							var errors = {};

							var message = (previous.message = response || validator.defaultMessage( element, "remote" ));

							errors[element.name] = DTjQuery.isFunction(message) ? message(value) : message;

							validator.showErrors(errors);

						}

						previous.valid = valid;
                         valid = true ;
						validator.stopRequest(element, valid);
			 }

		  },
		  required :false
		  
      });
	<?php } ?>
	/* DTjQuery('#next').live('click',function(){
	     console.log("enter");
		 DTjQuery('#discount_code').rules('remove','remote');
		 if(DTjQuery(documnent.frmcart).valid()){
		   console.log("valid");
		 }
		 	 
	 });*/
	
 })

</script>