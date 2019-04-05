<?php
$phone = get_field( 'phone' );
$tel = preg_replace('/[^\d]/', '', $phone );
?>
<a href="tel:<?php echo $tel; ?>"><?php echo $phone ?></a>
