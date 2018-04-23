<div id="<?php echo esc_attr( $this->id ) ?>" class="settings-page wrap modal slide" aria-hidden="true">
	<div class="modal__overlay" tabindex="-1" data-micromodal-close>
		<div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="settings-page-wrap-title" >
			<header class="modal__header">
				<h2 id="settings-page-wrap-title" class="modal__title">
					<?php printf( '%s %s', $this->title, __( 'Settings', 'rcno-reviews' ) ); ?>
				</h2>
				<button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
			</header>

			<div id="settings-page-wrap-content" class="modal__content">
				<form method="post" action="options.php">
					<?php settings_fields( 'rcno-author-box' ); ?>
					<?php do_settings_sections( 'rcno-author-box' ); ?>
					<table class="form-table">

						<tr valign="top">
							<th scope="row"><?php _e( 'Author box title', 'rcno-reviews' ); ?></th>
							<td>
								<input type="text"
								       name="rcno_author_box_options[author_box_title]"
								       class="regular-text"
								       value="<?php echo esc_attr( $this->get_setting( 'author_box_title' ) ); ?>"
								/>
							</td>
						</tr>

                        <tr valign="top">
                            <th scope="row"></th>
                            <td>
                                <p>
                                    <?php echo 'Visit your user <a href="' . get_edit_user_link() . '">profile page</a> to add your social media account information'; ?>
                                </p>
                            </td>
                        </tr>

						<tr valign="top">
							<th scope="row"><?php _e( 'Facebook', 'rcno-reviews' ); ?></th>
							<td>
                                <div class="rcno-settings-text-group">
                                    <div class="rcno-settings-text-wrapper">
                                        <input type="text"
                                               name="rcno_author_box_options[facebook_color]"
                                               class="rcno-settings-text rcno-color-select"
                                               value="<?php echo esc_attr( $this->get_setting( 'facebook_color', '#3B5998' ) ); ?>"
                                        />
                                    </div>
                                    <div class="rcno-settings-text-wrapper">
                                        <input type="hidden"
                                               name="rcno_author_box_options[facebook_url]"
                                               class="rcno-settings-text"
                                               value="<?php echo esc_attr( $this->get_setting( 'facebook_url' ) ); ?>"
                                        />
                                    </div>
                                </div>
							</td>
						</tr>

                        <tr valign="top">
                            <th scope="row"><?php _e( 'Twitter', 'rcno-reviews' ); ?></th>
                            <td>
                                <div class="rcno-settings-text-group">
                                    <div class="rcno-settings-text-wrapper">
                                        <input type="text"
                                               name="rcno_author_box_options[twitter_color]"
                                               class="rcno-settings-text rcno-color-select"
                                               value="<?php echo esc_attr( $this->get_setting( 'twitter_color', '#4099FF' ) ); ?>"
                                        />
                                    </div>
                                    <div class="rcno-settings-text-wrapper">
                                        <input type="hidden"
                                               name="rcno_author_box_options[twitter_url]"
                                               class="rcno-settings-text"
                                               value="<?php echo esc_attr( $this->get_setting( 'twitter_url' ) ); ?>"
                                        />
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><?php _e( 'Google+', 'rcno-reviews' ); ?></th>
                            <td>
                                <div class="rcno-settings-text-group">
                                    <div class="rcno-settings-text-wrapper">
                                        <input type="text"
                                               name="rcno_author_box_options[google_color]"
                                               class="rcno-settings-text rcno-color-select"
                                               value="<?php echo esc_attr( $this->get_setting( 'google_color', '#C61800' ) ); ?>"
                                        />
                                    </div>
                                    <div class="rcno-settings-text-wrapper">
                                        <input type="hidden"
                                               name="rcno_author_box_options[google_url]"
                                               class="rcno-settings-text"
                                               value="<?php echo esc_attr( $this->get_setting( 'google_url' ) ); ?>"
                                        />
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><?php _e( 'LinkedIn', 'rcno-reviews' ); ?></th>
                            <td>
                                <div class="rcno-settings-text-group">
                                    <div class="rcno-settings-text-wrapper">
                                        <input type="text"
                                               name="rcno_author_box_options[linkedin_color]"
                                               class="rcno-settings-text rcno-color-select"
                                               value="<?php echo esc_attr( $this->get_setting( 'linkedin_color', '#0E76A8' ) ); ?>"
                                        />
                                    </div>
                                    <div class="rcno-settings-text-wrapper">
                                        <input type="hidden"
                                               name="rcno_author_box_options[linkedin_url]"
                                               class="rcno-settings-text"
                                               value="<?php echo esc_attr( $this->get_setting( 'linkedin_url' ) ); ?>"
                                        />
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><?php _e( 'Tumblr', 'rcno-reviews' ); ?></th>
                            <td>
                                <div class="rcno-settings-text-group">
                                    <div class="rcno-settings-text-wrapper">
                                        <input type="text"
                                               name="rcno_author_box_options[tumblr_color]"
                                               class="rcno-settings-text rcno-color-select"
                                               value="<?php echo esc_attr( $this->get_setting( 'tumblr_color', '#34526F' ) ); ?>"
                                        />
                                    </div>
                                    <div class="rcno-settings-text-wrapper">
                                        <input type="hidden"
                                               name="rcno_author_box_options[tumblr_url]"
                                               class="rcno-settings-text"
                                               value="<?php echo esc_attr( $this->get_setting( 'tumblr_url' ) ); ?>"
                                        />
                                    </div>
                                </div>
                            </td>
                        </tr>

					</table>
					<footer class="modal__footer">
						<?php submit_button(); ?>
					</footer>
				</form>
			</div>

		</div>
	</div>
</div>

<style>
    .rcno-settings-text-group {
        display: flex;
        flex-wrap: wrap;
        justify-content: start;
    }
    .rcno-settings-text-wrapper .minicolors {
        width: 50px;
    }
    .rcno-settings-text-group input {
        width: 100%;
    }
    .rcno-settings-text-wrapper:nth-child(2) {
        flex: 0 1 66%;
    }
    .rcno-settings-text-wrapper:nth-child(1) {
        flex: 0 1 30%;
        margin-right: 10px;
    }

</style>
