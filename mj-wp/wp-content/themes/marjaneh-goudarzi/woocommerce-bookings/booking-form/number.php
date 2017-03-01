<?php extract( $field ); ?>
<span class="form-field form-field-wide large-12 columns <?php echo implode( ' ', $class ); ?>">
	<label for="<?php echo $name; ?>"><?php
		if ($name == 'wc_bookings_field_persons') {
			echo "<p>How many people will be attending?</p>";
		} else {
			echo $name;
		}
	?></label>
	<input

		type="number"
		value="0"
		step="<?php echo ( isset( $step ) ) ? $step : ''; ?>"
		min="<?php echo ( isset( $min ) ) ? $min : ''; ?>"
		max="<?php echo ( isset( $max ) ) ? $max : ''; ?>"
		name="<?php echo $name; ?>"
		id="<?php echo $name; ?>"
		/> <?php echo ( ! empty( $after ) ) ? $after : ''; ?>
</span>
