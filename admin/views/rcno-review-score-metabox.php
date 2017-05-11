<?php

?>

<table class="form-table">
	<tr>
		<th scope="row">Review Score</th>
		<td>
			<input type="checkbox" id="rcno_reviews_score_enable" name="rcno_reviews_score_enable" value="1" checked="checked"><br>
			<label for="rcno_reviews_score_enable">Check this to enable review system for this post.</label>
		</td>
	</tr>

	<tr>
		<th scope="row">Review Score Position</th>
		<td>
			<select id="rcno_reviews_score_position" name="rcno_reviews_score_position">
				<option value="bottom">Bottom</option>
				<option value="top" selected="selected">Top</option>
			</select><br>
			<label for="rcno_reviews_score_position">Position of review score box in a single review.</label>
		</td>
	</tr>

	<tr>
		<th scope="row">Review Criteria</th>
		<td><?php
				$rows = array();

				if($meta) {
					$rows = $meta;
				}

				$c = 0;

				if ( count( $rows ) > 0 ) {
					foreach( $rows as $row ) {
						if ( isset( $row['c_label'] ) || isset( $row['score'] ) ) {
							echo '
							<p>
								<label for="' . $field['id'] . '[' . $c .'][c_label]">Label: </label>
								<input type="text" name="' . $field['id'] . '[' . $c . '][c_label]" value="' . $row['c_label'] . '" />
								<label for="' . $field['id'] . '[' . $c . '][score]">Score: </label>
								<input type="text" name="' . $field['id'] . '[' . $c . '][score]" value="' . $row['score'] . '" />
								<a class="remove button-secondary">Remove</a>
							</p>';
							$c = $c + 1;
						}
					}
				}
				echo '<span id="criteria-placeholder"></span>';
				echo '<a class="add-criteria button-primary" href="#">Add Criteria</a>';
				echo '<br /><span style="margin-top: 10px; display: block;" class="description">' . $field['desc'] . '</span>';
			?>
			<script>
                var $ = jQuery.noConflict();
                $(document).ready(function() {
                    var count = <?php echo $c; ?>;
                    $('.add-criteria').click(function() {
                        count = count + 1;

                        $('#criteria-placeholder')
	                        .append('<p><label for="<?php echo $field['id']; ?>['+count+'][c_label]">Label: </label><input type="text" name="<?php echo $field['id']; ?>['+count+'][c_label]" value="" /><label for="<?php echo $field['id']; ?>['+count+'][score]">Score: </label><input type="text" name="<?php echo $field['id']; ?>['+count+'][score]" value="" /><a class="remove button-secondary">Remove</a></p>');
                        return false;
                    });

                    $('.remove').live('click', function() {
                        $(this).parent().remove();
                    });
                });
			</script>
			<label for="rcno_reviews_score_criteria">Position of review score box in a single review.</label>
		</td>
	</tr>

	<tr>
		<th scope="row">Review Score Type</th>
		<td>
			<select id="rcno_reviews_score_type" name="rcno_reviews_score_type">
				<option value="number">Letter</option>
				<option value="letter" selected="selected">Letter</option>
				<option value="star">Star</option>
			</select><br>
			<label for="rcno_reviews_score_type">Select the book review rating type.</label>
		</td>
	</tr>


</table>