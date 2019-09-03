<div id=icaption>
	<div id=title><?php echo $data->title;?></div>
	<?php 
		foreach ($data->opButtons as $button){
			switch ($button['type'])
			{
				case 'add':
					$btnType = 'btn_add';
					$title = "新增";
					break;
				case 'back':
					$btnType = 'btn_back';
					$title = "返回";
					break;
				case 'add_back':   
					$btnType="btn_add_back"       ;    
					$btnTypeAdd="btn_add"        ;      
					$btnTypeBack="btn_back"        ;         //新增和返回
					break;
				case 'add_add_back':
					$btnType="btn_add_add_back"       ;
					$btnTypeAdd1="btn_add1"        ;
					$btnTypeAdd2="btn_add2"        ;
					$btnTypeBack="btn_back"        ;         //新增和返回
					break;
					
				case 'edit':
					$btnType = 'btn_edit';
					$title = "编辑";
					break;
				case 'import':
					$btnType = 'btn_import';
					$title = "导入";
					break;
				case 'export':
					$btnType = 'btn_export';
					$title = "导出";
					break;
				case 'preview':
					$btnType = 'btn_preview';
					$title = "预览";
					break;
				default:
					break;				
			}
			
			if (!$btnType) continue;
	?>
	<?php 
	if($btnType=="btn_add_back"){ ?>
		<a href="<?php echo $button['addurl']?>"  title="<?php echo $title?>" <?php if($btnType=="btn_preview"){?>target="_blank"<?php }?>  id=<?php echo $btnTypeAdd?>></a>
			<a href="<?php echo $button['backurl']?>"  title="<?php echo $title?>" <?php if($btnType=="btn_preview"){?>target="_blank"<?php }?>  id=<?php echo $btnTypeBack?>></a>
	
	<?php }?>
	<?php 
		 if($btnType=="btn_add_add_back"){ ?>
		<a href="<?php echo $button['addurl1']?>"  title="<?php echo $title?>" <?php if($btnType=="btn_preview"){?>target="_blank"<?php }?>  id=<?php echo $btnTypeAdd1?>></a>
		<a href="<?php echo $button['addurl2']?>"  title="<?php echo $title?>" <?php if($btnType=="btn_preview"){?>target="_blank"<?php }?>  id=<?php echo $btnTypeAdd2?>></a>
			<a href="<?php echo $button['backurl']?>"  title="<?php echo $title?>" <?php if($btnType=="btn_preview"){?>target="_blank"<?php }?>  id=<?php echo $btnTypeBack?>></a>
	
	<?php }?>



	
	<a href="<?php echo $button['url']?>"  title="<?php echo $title?>" <?php if($btnType=="btn_preview"){?>target="_blank"<?php }?>  id=<?php echo $btnType?>></a>
	<?php }?>
</div>