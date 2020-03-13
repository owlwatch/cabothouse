<?php

$labels = [
	'M','Tu','W','Th','F','Sa','Su'
];

// lets get the hours
$week = get_field( 'hours', is_singular('location') ? get_queried_object_id() : get_the_ID() );
// these are organized by index: 0-6 = M-Su

$hours_note = get_field('hours_note', is_singular('location') ? get_queried_object_id() : get_the_ID());

// lets let them know if CH is open right now
$day = (int)current_time( 'w' );
$time = current_time( 'U' );
?>
<div class="store-hours">
<?php
if( is_array( $week ) ) foreach( array_values($week) as $i => $hours ){
	$classes = ['store-hours--day'];
	if( $i === $day ){
		$classes[] = 'store-hours--current-day';
	}
	?>
	<div class="<?php echo implode( $classes ); ?>">
		<div class="store-hours--day-label">
			<?php
			echo $labels[$i];
			?>
		</div>
		<div class="store-hours--day-value">
			<?php
			if( $hours['closed'] ){
				if( $hours['special_note'] ){
					echo $hours['special_note'];
				}
				else {
					echo 'Closed';
				}
			}
			else {
				echo $hours['open'].' - '.$hours['close'];
			}
			?>
		</div>
	</div>
	<?php
}
?>
</div>

<?php if( $hours_note ){ ?>
	<div class="store-hours__note">
		<p><?php echo $hours_note; ?></p>
	</div>
	<?php } ?>
