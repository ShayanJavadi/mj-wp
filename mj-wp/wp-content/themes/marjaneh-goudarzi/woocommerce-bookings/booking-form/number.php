<?php extract( $field ); ?>
<p class="form-field form-field-wide large-6 columns <?php echo implode( ' ', $class ); ?>">
	<label for="<?php echo $name; ?>"><?php echo $label; ?>:</label>
	<input
		type="number"
		value="<?php echo ( ! empty( $min ) ) ? $min : 0; ?>"
		step="<?php echo ( isset( $step ) ) ? $step : ''; ?>"
		min="<?php echo ( isset( $min ) ) ? $min : ''; ?>"
		max="<?php echo ( isset( $max ) ) ? $max : ''; ?>"
		name="<?php echo $name; ?>"
		id="<?php echo $name; ?>"
		/> <?php echo ( ! empty( $after ) ) ? $after : ''; ?>
</p>
