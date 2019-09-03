<?php 
foreach ($model as $item){
	echo $item->form_sn;
	?>	
	</br>
	
<?php 	
}
paginate($page, "contract_page");
?>