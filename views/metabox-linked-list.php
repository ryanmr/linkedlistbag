
<?php
	$this->print_nonce();
?>

<table class="widefat">
	
	<tr>
		<th><label>Source</label></th>
		<td>
			<input type="text" class="widefat" name="<?php echo \LinkedList\Meta::SOURCE_URL; ?>" value="<?php echo esc_attr(\LinkedList\Meta::get_instance()->get_source_url()); ?>" />
			<p>The URL for the original source.</p>
		</td>
	</tr>
	
	<tr>
		<th><label>Via</label></th>
		<td>
			<input type="text" class="widefat" name="<?php echo \LinkedList\Meta::VIA_URL; ?>" value="<?php echo esc_attr(\LinkedList\Meta::get_instance()->get_via_url()); ?>" />
			<p>The URL for the origin of discovery.</p>
		</td>
	</tr>	
	
</table>