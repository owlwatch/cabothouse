<?php
$address_str = str_replace( "\n", ' ', strip_tags( get_field( 'address' ) ));
$google_map_link = 'https://www.google.com/maps/search/?api=1&query='.urlencode( 'Cabot House '.$address_str );
?>
<span class="store-address">
<?php echo get_field( 'address' ); ?>
</span><br />
<a href="<?php echo $google_map_link; ?>" target="_blank">
	Google Map <span class="fa fa-external-link sup"></span>
</a>
