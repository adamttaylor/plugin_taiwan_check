jQuery(document).ready(function(){

$twn = jQuery('#taiwan_catch_nonce');
jQuery('form#post').append($twn);
//.. Moving the nonce inside the form. There is probably a better way in php to do this

$path = window.location.pathname;
  //$agree_check
  if($path.indexOf('post-new.php') != -1 || $path.indexOf('post.php') != -1){
    if(check_tjs && !check_tjs.checked){
      addWarning();
      jQuery('input,textarea').on('keyup',lookForTaiwan);
      jQuery('input[name=tai_checked]').on('change',acknowledged)
    }
  }
  //Add warning box above the publish
  function addWarning(){
    jQuery('#major-publishing-actions').prepend(
      '<div class="warn-user" style="display:none;text-indent: -20px;margin-left: 20px;margin-bottom: 10px;padding-bottom: 10px;border-bottom: 1px solid;"><input type="checkbox" name="tai_checked" style="position: relative;top: 4px;"><label for="_tai_checked">Please be aware that Taiwan needs to be referred to using the UN nomenclature of \'Taiwan, province of China\' - please click here to acknowledge this message.</label></div>');
  }
  function stringHasTaiwan(elem){
    $val = elem.value.toLowerCase()
    return $val.indexOf('taiwan') != -1 && $val.indexOf('taiwan, province of china')==-1;
  }
  //search for taiwain
  function lookForTaiwan(e){
    $found = false;
     jQuery('input,textarea').each(function(i, elem){
      if(stringHasTaiwan(elem)){
        $found = true;
      }
     })
     if($found){
      showWarning()
     }else{
      hideWarning()
     }
  }
  function acknowledged(){
    console.log('clicked')
    if(jQuery('input[name=tai_checked]:checked').length){
      //hideWarning()
      jQuery('#major-publishing-actions input[type=submit]').removeAttr('disabled')
    }
  }
  function showWarning(){
    jQuery('#major-publishing-actions .warn-user').show()
      if(!jQuery('input[name=tai_checked]:checked').length){
      jQuery('#major-publishing-actions input[type=submit]').attr('disabled','disabled')
    }
  }
  function hideWarning(){
    jQuery('#major-publishing-actions .warn-user').hide()
    jQuery('#major-publishing-actions input[type=submit]').removeAttr('disabled')
  }
})