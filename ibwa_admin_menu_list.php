<?php
global $wpdb;
global $woocommerce;

if(isset($_POST["UploadCSV"])) {
 $defval = '/uploads/';
//$target_file = IBWA_FILE_PATH .'/'. basename($_FILES["uploadfilecsv"]["name"]);
$attachment_path = dirname(__FILE__).$defval.$_FILES["uploadfilecsv"]["name"];

if (move_uploaded_file($_FILES['uploadfilecsv']['tmp_name'], $attachment_path)) {   
  //Open the file.
 $fileHandle = fopen($attachment_path, "r");
$termsarra = array();
$arrayes_of_attributes = array();
//Loop through the CSV rows.
$s=0;

while (($row = fgetcsv($fileHandle)) !== FALSE) {
	
$maincay = explode(":",$row[0]);
$mai_cat = $maincay[0];
//child texonomy
$CHILDcay = explode(",",$maincay[1]);
//echo '<br/>';
$arrayes_of_attributes[$s] = $mai_cat;
//find slug of woocommerce attribute
$m=0;
foreach($CHILDcay as $key=>$valu){
	
	/*  $querystr2id = "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_id='".trim($createdid)."'";
	  $pageposts_id = $wpdb->get_results($querystr2id);	
				if(count($pageposts_id) >0){ */
	
	                    $termsarra[$mai_cat][$m] = trim($valu);

	$m++;
}
	$s++;
	//while end
} 

fclose($fileHandle);
unlink($attachment_path);

/*  print_r( $arrayes_of_attributes); 
echo '<br/>'; 
 print_r( $termsarra); */ 

 $createdid =''; 
//echo $createdid;

foreach($arrayes_of_attributes as $vls){
	//print_r($vls);
	settremmain($vls);
}

foreach($termsarra as $key=>$valu){
ob_start();
		//print_r($key);
   $fourt =  settremmain($key);
		
	foreach($valu as $vbn){
			
            $getparted = childter($fourt,$vbn);
			
			if($getparted =='rx'){
			echo '<div class="erner_on_upload"> Terms Alreday Exists</div>';
			}
			 
		}
		 
      //terms of attribute
}  

    echo '<div class="success_on_upload">CSV uploaded successfully.</div>';
    } else {
       echo "<div class='error_on_upload'>Upload Failed</div>";
    }

}

function settremmain($key){
global $wpdb;
global $woocommerce;
			
$querystr2 = "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_label='".trim($key)."'";
$pageposts = $wpdb->get_results($querystr2);
	//echo count($pageposts);
	 if(count($pageposts) > 0){
       // print_r($pageposts[0]->attribute_id);	
       return $createdid = $pageposts[0]->attribute_id;   
		 // run loop
	 }else{
		 
		 $attribute_name = str_replace(" ", "-",strtolower(trim($key)));
		 $attribute_label = trim($key);
		 $attribute_type = 'select';
		 $attribute_orderby = 'menu_order';
		 $attribute_public = 0;

		$args      = array(
			'name'         => $attribute_label,
			'slug'         => $attribute_name,
			'type'         => $attribute_type,
			'order_by'     => $attribute_orderby,
			'has_archives' => $attribute_public,
		);

		 $createdid = wc_create_attribute( $args );
		 
		 
        flush_rewrite_rules();
       delete_transient('wc_attribute_taxonomies');
			
		  return $createdid;
	 }
	 
	
}

function childter($createdid, $vbn){
global $wpdb;
global $woocommerce;
	
		$querystr2id = "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_id='".trim($createdid)."'";
	  $pageposts_id = $wpdb->get_results($querystr2id);	
				if(count($pageposts_id) >0){
			//echo $vbn;
			//echo $pageposts_id[0]->attribute_name;
			 $taxonomy = 'pa_'.$pageposts_id[0]->attribute_name;
			 
			 $vnm_slg = str_replace(" ", "-",strtolower(trim($vbn)));
			 
			//$sql = "INSERT INTO {$wpdb->prefix}terms (name, slug, term_group) VALUES ('$vbn', '$vnm_slg', 0)";
			//$sql2 = "INSERT INTO {$wpdb->prefix}term_taxonomy (nameIndex, slug, term_group) VALUES ('$vbn', '$vnm_slg', 0)";
			
	 $querystr2id2 = "SELECT * FROM {$wpdb->prefix}terms WHERE name='".trim($vbn)."'";
	 $pageposts_id2 = $wpdb->get_results($querystr2id2);	
				if(count($pageposts_id2) >0){ 
				 return 'rx';
				}else{
			
			 $wpdb->insert( 
						"{$wpdb->prefix}terms", 
						array( 
							'name' => $vbn,
							'slug' => $vnm_slg,
							'term_group'=>0
						)
					); 

          $this_insert = $wpdb->insert_id;
		  
		  $wpdb->insert( 
						"{$wpdb->prefix}term_taxonomy", 
						array( 
							'term_id' => $this_insert,
							'taxonomy' => $taxonomy,
							'parent'=>0,
							'count'=>0
						)
					);
				}
			 
	 	/* 	 $Mcid = wp_insert_term( 
		                 $vbn, // the term 
						 'pa_'.$pageposts_id[0]->attribute_name, // the taxonomy
							array(
								 'description'=> '',
								  'slug' => str_replace(" ", "-",strtolower(trim($vbn))),
								 'parent' => '' 
							  )
							);
									
						  if ( ! is_wp_error( $Mcid ) )
							  {
								  
								 $finded = isset( $Mcid['term_id'] ) ? $Mcid['term_id'] : 0;
								
							  }else{
										 // Trouble in Paradise:
										 echo "<div class='error_on_upload'>".$Mcid->get_error_message()."</div>";
									} 
			 */
				} 
	
}

?>

<div class="wpbody">
<div class="wpbody-content">
<div class="wrap">
<h1>Import Bulk WooCommerce Attributes</h1>
<form method="post" name="importatter" id="importatter" action="" enctype="multipart/form-data" >

<table class="form-table">

<tr>
<td>Upload CSV: </td>
<td><input type="file" name="uploadfilecsv" id="ibw_uploadfilecsv" class="uploadfilecsv button" /> </td>
</tr>

<tr>
<td> </td>
<td><input type="submit" name="UploadCSV" value="Upload CSV" id="upcsv" class="button button-primary" /> </td>
</tr>

</table>
</form>

<div class="explaefile">
<div class="ddrefg">
<?php
$filepa = IBWA_URL.'/example.csv';
?>
Download Sample File From <a href="<?php echo $filepa; ?>" download>HERE</a> 
</div>
</div>

</div>
</div>
</div>