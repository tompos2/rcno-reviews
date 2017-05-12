<?php

// Get the review ID.
if ( isset( $GLOBALS['review_id'] ) && $GLOBALS['review_id'] !== '' ) {
	$review_id = $GLOBALS['review_id'];
} else {
	$review_id = get_post()->ID;
}

do_action( 'before_the_rcno_book_description' );

Rcno_Template_Tags::the_rcno_book_description();

Rcno_Template_Tags::rcno_print_review_box( $review_id, true );

Rcno_Template_Tags::rcno_print_review_badge( $review_id, true );

var_dump( __FILE__ );

?>

<style>
	#rcno-review-score-box {
		background: #f5f2dd;
		padding: 10px;
	}

	#rcno-review-score-box ul {
		list-style: none;
	}

	#rcno-review-score-box .review-summary {
		margin: 0 0 1em 0;
	}

	#rcno-review-score-box ul li .rcno-review-score-bar-container {
		height: 40px;
		background: #ccc;
		overflow: hidden;
		margin: 0 0 1em 0;
		position: relative;
	}

	#rcno-review-score-box ul li .review-score-bar {
		background: green;
	}

	#rcno-review-score-box ul li .review-score-bar span.score-bar {
		font-size: 14px;
		font-weight: bold;
		color: #fff;
		line-height: 40px;
		padding: 0 0 0 10px;
		text-transform: uppercase;
	}

	#rcno-review-score-box ul li .rcno-review-score-bar-container span.right {
		font-size: 16px;
		font-weight: bold;
		color: #fff;
		z-index: 9999;
		position: absolute;
		right: 10px;
		top: 6px;
	}

</style>
