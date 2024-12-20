<?php
defined('ABSPATH') || exit();
//INCLUDED IN CLASS CSS


if($global_animations && $json_settings['animations_page'] !== 'none'){
    $css .= '
    .uicore-animation-bg{
        background-color:' . $this->color($json_settings['animations_page_color']) . ';
    }
    ';
    if($json_settings['animations_page'] === 'fade'){
        $css .='
        #uicore-page {
            opacity: 0;
            animation-name: uicoreFadeIn;
            animation-fill-mode: forwards;
            animation-timing-function: ease-in;
        ';
        if($json_settings['animations_page_duration'] === 'fast'){
            $css .='animation-duration: 0.15s;';
        }elseif($json_settings['animations_page_duration'] === 'slow'){
            $css .='animation-duration: 0.8s;';
        }else{
            $css .='animation-duration: 0.35s;';
        }
        $css .='}';
    }
    if($json_settings['animations_page'] === 'fade in'){
        $css .='
        #uicore-page {
            opacity: 0;
            animation-name: uicoreFadeIn;
            animation-fill-mode: forwards;
            animation-timing-function: ease-in;
        ';
        if($json_settings['animations_page_duration'] === 'fast'){
            $css .='animation-duration: 0.1s;';
        }elseif($json_settings['animations_page_duration'] === 'slow'){
            $css .='animation-duration: 0.6s;';
        }else{
            $css .='animation-duration: 0.2s;';
        }
        $css .='}';
    }
    if($json_settings['animations_page'] === 'reveal'){
        $css .='
        .uicore-animation-bg {
			position: fixed;
			top: 0;
			left: 0;
			width: 100vw;
			height: 100vh;
			display: block;
			pointer-events: none;
			transform: scaleX(0);
			animation-fill-mode: forwards;
			transform-origin: right center;
			animation-name: uiCoreAnimationsReveal;
			animation-play-state: paused;
			z-index: 99999999999999999999;
			animation-timing-function: cubic-bezier(0.87, 0, 0.13, 1);
        ';
        if($json_settings['animations_page_duration'] === 'fast'){
            $css .='animation-duration: 0.4s;';
        }elseif($json_settings['animations_page_duration'] === 'slow'){
            $css .='animation-duration: 1.2s;';
        }else{
            $css .='animation-duration: 0.65s;';
        }
		$css .='}';
    }
    if($json_settings['animations_page'] === 'fade and reveal'){
        $css .='
        .uicore-animation-bg {
			position: fixed;
			top: 0;
			left: 0;
			width: 100vw;
			height: 100vh;
			display: block;
			pointer-events: none;
			transform: scaleX(0);
			animation-fill-mode: forwards;
			transform-origin: right center;
			animation-timing-function: cubic-bezier(0.87, 0, 0.13, 1);
			animation-name: uiCoreAnimationsRevealBottom;
			animation-play-state: paused;
			z-index: 99999999999999999999;
        ';
        if($json_settings['animations_page_duration'] === 'fast'){
            $css .='animation-duration: 0.75s;';
        }elseif($json_settings['animations_page_duration'] === 'slow'){
            $css .='animation-duration: 1.2s;';
        }else{
            $css .='animation-duration: 0.9s;';
        }
		$css .='}';
    }
}






if($global_animations && $json_settings['animations_ham'] !== 'fade in'){
    $css .= '
    .uicore-ham-reveal{
        background-color:' . $this->color($json_settings['animations_ham_color']) . ';
    }
    ';
}
if( $json_settings['animations_footer_delay'] && $json_settings['animations_footer'] !='none'){
    $css .= '
    .uicore-footer-wrapper .uicore-animate {
        animation-delay: '.$json_settings['animations_footer_delay'].'ms
    }
    ';
}

if( $json_settings['animations_shop_delay_child'] && $json_settings['animations_shop'] !='none'){
    $css .= '
    main {
        --uicore-animations--shop-delay: '.$json_settings['animations_shop_delay_child'].'ms
    }
    ';
}
