<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> &gt; <a href="describe_columnfamily.php?keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>"><?=$columnfamily_name?></a> &gt; <?php if ($mode == 'insert'): ?>Insert a Row<?php elseif ($mode == 'edit'): ?>Edit Row "<?php echo $key; ?>"<?php endif; ?></h3>

<script type="text/javascript">
	var num_columns = 0;
	var num_super_columns = 0;

	function addSuperColumn(super_key) {
		num_columns = 0;
		num_super_columns++;
		
		$('#data').append('<div id="' + num_super_columns + '_super_column_data" style="border-bottom: 1px solid #000; margin-bottom: 10px; padding-bottom: 10px;"></div>');
		$('#' + num_super_columns + '_super_column_data').append('<div><label for="key">Super Column Key:</label><input id="column_key" name="column_key_' + num_super_columns + '" type="text" value="' + super_key + '" /></div>');
	}
	
	function addColumn(name,value,p_num_super_columns) {
		if (num_columns == 0) {		
			if ($('#' + p_num_super_columns + '_super_column_data').length == 0) {
				$('#data').append('<div id="' + num_super_columns + '_super_column_data" style="border-bottom: 1px solid #000; margin-bottom: 10px; padding-bottom: 10px;"></div>');
			}
		
			$('#' + p_num_super_columns + '_super_column_data').append('<div><label for="column_name">Column Name:</label><span id="column_name">Column Value:</span></div>');
		}
		
		num_columns++;		
		$('#' + p_num_super_columns + '_super_column_data > .btn_add_column').remove();		
		$('#' + p_num_super_columns + '_super_column_data').append('<div class="clear_left"></div><div class="insert_row_column_name"><input id="column_name_' + num_super_columns + '_' + num_columns + '" name="column_name_' + num_super_columns + '_' + num_columns + '" type="text" class="smaller" value="' + name + '" /></div><input id="column_value_' + num_super_columns + '_' + num_columns + '" name="column_value_' + num_super_columns + '_' + num_columns + '" type="text" class="smaller" value="' + value + '" /> <input class="btn_add_column" type="button" value="Add..." onclick="addColumn(\'\',\'\',\'' + p_num_super_columns + '\');" />');
	}

	$(document).ready(function() {		
		<?php if ($mode == 'insert'): ?>
			<?php if ($is_super_cf): ?>
				addSuperColumn('<?php echo $super_key; ?>',1);
			<?php else: ?>
				num_super_columns++;
			<?php endif; ?>
			
			addColumn('','',1);
		<?php endif; ?>
		<?php 
			if ($mode == 'edit'):
				if ($is_super_cf):
					foreach ($output as $super_key => $data):
						echo 'addSuperColumn(\''.$super_key.'\');';
						foreach ($data as $name => $value):
							echo 'addColumn(\''.$name.'\',\''.$value.'\',num_super_columns);';
						endforeach;
					endforeach;
				else:
					echo 'num_super_columns++;';
					foreach ($output as $name => $value):
						echo 'addColumn(\''.$name.'\',\''.$value.'\',num_super_columns);';
					endforeach;
				endif;
			endif;
		?>
	});
</script>

<?=$success_message?>
<?=$info_message?>
<?=$error_message?>

<form method="post" action="">
	<div>
		<label for="key">Row Key:</label>
		<input id="key" name="key" type="text" value="<?php echo $key; ?>" <?php if ($mode == 'edit'): ?>disabled="disabled"<?php endif;?> />
	</div>
	
	<div id="data"></div>
	
	<div>
		<input type="submit" name="btn_insert_row" value="<?php if ($mode == 'insert'): ?>Insert Row<?php elseif ($mode == 'edit'): ?>Edit Row<?php endif; ?>" />
	</div>
	
	<?php if ($mode == 'edit'): ?><input type="hidden" name="key" value="<?php echo $key; ?>" /><?php endif;?>
	<input type="hidden" name="keyspace_name" value="<?=$keyspace_name?>" />
	<input type="hidden" name="columnfamily_name" value="<?=$columnfamily_name?>" />
	<input type="hidden" name="mode" value="<?php echo $mode; ?>" />
</form>