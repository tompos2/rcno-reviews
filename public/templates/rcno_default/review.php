<?php

$review = new Rcno_Template_Tags();

do_action( 'before_the_rcno_book_description' );

$review->the_rcno_book_description();

echo __FILE__ . '<br />';