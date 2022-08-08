<?php

function formsapp_shortcode_handler($atts)
{
    return '<iframe id="" allowtransparency="true" allowfullscreen="true"
    allow="geolocation; microphone; camera" src="https://my.forms.app/form/' . $atts['id'] . '"
    frameborder="0" style="width: 1px; min-width:100%; height:750px; border:none;"></iframe>';
}

add_shortcode('formsapp', 'formsapp_shortcode_handler');

