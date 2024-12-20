<?php
defined('ABSPATH') || exit();
//INCLUDED IN CLASS JS
if($settings['menu_interaction'] === 'focus'){
  $js .= "
  document.body.classList.add('uicore-menu-focus');
  ";
}elseif($settings['menu_interaction'] === 'text flip'){
    $js .= "
    jQuery('.uicore-menu li').on('mouseenter',function(e) {
        var animClass = 'ui-anim-flip';
        if(!jQuery(this).hasClass(animClass)){
          
          var btnContent = jQuery(this).children('a').find('.ui-menu-item-wrapper')
          btnContent.after(btnContent.clone())
          jQuery(this).children('a').find('.ui-menu-item-wrapper').wrapAll('<div class=\"ui-flip-anim-wrapp\"></div>');
          setTimeout(() => {
            this.classList.add(animClass)
          }, 10)
        }
      })
    ";
}