<?php

$author             = get_the_author();
$author_description = wpautop( get_the_author_meta( 'description' ) );
$author_url         = get_the_author_meta( 'url' );
$author_archive     = get_author_posts_url( get_the_author_meta( 'ID' ) );
$gravatar           = get_avatar_url( get_the_author_meta( 'user_email' ) );

$out = '';
$out .= '<div class="' . $this->id . ' rcno-author-box-container">';

$out .= '<div class="rcno-author-box-gravatar-container">';
$out .= '<img class="rcno-author-box-gravatar" src="' . $gravatar . '">';
$out .= '</div>'; // .rcno-author-box-gravatar-container

$out .= '<div class="rcno-author-box-bio-container">';
$out .= '<h3>' . $this->get_setting( 'author_box_title' ) . ' ' . $author . '</h3>';
$out .= $author_description;


$out .= '<div class="rcno-author-social-icons">';

if ( '' !== $this->get_setting( 'facebook_url' ) ) { // Facebook
	$out .= '<a href="' . esc_attr( $this->get_setting( 'facebook_url' ) ) . '" rel="noreferrer noopener" ';
	$out .= 'style="background: ' . esc_attr( $this->get_setting( 'facebook_color' ) ) . ';" >';
	$out .= '<span class="social-icon socicon-facebook"></span>';
	$out .= '</a>';
}

if ( '' !== $this->get_setting( 'twitter_url' ) ) { // Twitter
	$out .= '<a href="' . esc_attr( $this->get_setting( 'twitter_url' ) ) . '" rel="noreferrer noopener" ';
	$out .= 'style="background: ' . esc_attr( $this->get_setting( 'twitter_color' ) ) . ';" >';
	$out .= '<span class="social-icon socicon-twitter"></span>';
	$out .= '</a>';
}

if ( '' !== $this->get_setting( 'google_url' ) ) { // Google+
	$out .= '<a href="' . esc_attr( $this->get_setting( 'google_url' ) ) . '" rel="noreferrer noopener" ';
	$out .= 'style="background: ' . esc_attr( $this->get_setting( 'google_color' ) ) . ';" >';
	$out .= '<span class="social-icon socicon-google-plus"></span>';
	$out .= '</a>';
}

if ( '' !== $this->get_setting( 'google_url' ) ) { // LinkedIn
	$out .= '<a href="' . esc_attr( $this->get_setting( 'linkedin_url' ) ) . '" rel="noreferrer noopener" ';
	$out .= 'style="background: ' . esc_attr( $this->get_setting( 'linkedin_color' ) ) . ';" >';
	$out .= '<span class="social-icon socicon-linkedin"></span>';
	$out .= '</a>';
}

if ( '' !== $this->get_setting( 'google_url' ) ) { // Tumblr
	$out .= '<a href="' . esc_attr( $this->get_setting( 'tumblr_url' ) ) . '" rel="noreferrer noopener" ';
	$out .= 'style="background: ' . esc_attr( $this->get_setting( 'tumblr_color' ) ) . ';" >';
	$out .= '<span class="social-icon socicon-tumblr"></span>';
	$out .= '</a>';
}

$out .= '</div>'; // .rcno-author-archive-link

$out .= '<div class="rcno-author-archive-link">';
$out .= '<a href="' . $author_archive . '" rel="author">';
$out .= sprintf( '%s %s &rarr;', __( 'View all posts by', 'rcno-reviews' ), $author );
$out .= '</a>';
$out .= '</div>'; // .rcno-author-archive-link

$out .= '</div>'; // .rcno-author-box-bio-container
$out .= '</div>'; // .rcno-author-box-container

echo $out;