<?php extract( $field ); ?>
<div class="form-field form-field-wide large-6 columns <?php echo implode( ' ', $class ); ?>">
	<label for="<?php echo $name; ?>"><?php echo $label; ?>:</label>
	<select name="<?php echo $name; ?>" id="<?php echo $name; ?>">
		<?php foreach ( $options as $key => $value ) : ?>
			<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
		<?php endforeach; ?>
	</select>
</div>
