<?php
class ControllerUserPriceUpload extends Controller {
	private $error = array();

	public function index() {

				echo '<meta charset="utf-8">';

echo 'Закрыто';
exit;
/**/
				$upl_prod = array();
				set_time_limit(120);
				$this->load->model('user_price/upload_vendor');
				//include_once('simple_html_dom.php');
				$a = false;
				$i = 0;
				$limit = 300;
				$json = array();


				$cikle_break = true;
				$a = false;
				$params = array();
				$inf = $inf2 = $inf_3 = '';
				$all_categoryes = $this->model_user_price_upload_vendor->getSiteCategoryes();
				$all_products = $this->model_user_price_upload_vendor->getSiteProducts();
				
				//$all_manufacturers = $this->model_user_price_upload_vendor->getManufacturers();
				//	$path = $_SERVER["DOCUMENT_ROOT"].'/image/catalog/products/'.$vendor;
				//$dir = $this->request->server['DOCUMENT_ROOT'].'/image/catalog/products/'.$vendor.'/';
				// print_r($all_products);


				$file_name = $_SERVER['DOCUMENT_ROOT'].'/upload/pironet.xlsx';

				set_include_path(get_include_path() . PATH_SEPARATOR .$_SERVER['DOCUMENT_ROOT'].'/admin/model/phpExel/Classes/');
				include_once 'PHPExcel.php';
				include("PHPExcel/Writer/Excel5.php");

			//	$all_vendor_site_products = $this->model_user_price_upload_vendor->getVendorProducts($vendor);
			//	$all_vendor_site_products = $this->model_user_price_upload_vendor->getVendorProducts_onSite($vendor);
			//	print_r($all_vendor_site_products);

				$xls = PHPExcel_IOFactory::load($file_name);
				$xls->setActiveSheetIndex(0);
				$objWorksheet = $xls->getActiveSheet();

				$chunkSize = 10000;      //размер считываемых строк за раз
				$startRow  = 20000;
				$empty_value = 0; 

				$products = []; 

			$products = $this->cache->get('piro_products');
				if(!count($products))
				 for ($i = $startRow; $i <= $startRow + $chunkSize; $i++)  //внутренний цикл по строкам 
					{

						$params = [];
						$artikul = $objWorksheet->getCellByColumnAndRow(0, $i)->getValue();
						$cat_lv1 = $objWorksheet->getCellByColumnAndRow(35, $i)->getValue();

						if(!$artikul){
							echo $i.'--END--<br>';
							break;
						}
						if(!$cat_lv1){
							continue;
						}

						$name = $objWorksheet->getCellByColumnAndRow(1, $i)->getValue();
						$active = $objWorksheet->getCellByColumnAndRow(3, $i)->getValue();
						$image = $objWorksheet->getCellByColumnAndRow(6, $i)->getValue();
						if($image) $image = 'catalog/products'.$image;
						$image_dop = $objWorksheet->getCellByColumnAndRow(9, $i)->getValue();
						if($image_dop) $image_dop = 'catalog/products'.$image_dop;
						$priviewText = $objWorksheet->getCellByColumnAndRow(7, $i)->getValue();
						$datailText = $objWorksheet->getCellByColumnAndRow(10, $i)->getValue();
						$code = $objWorksheet->getCellByColumnAndRow(12, $i)->getValue();

						$params[]= array('attrbute_id'=> 17, 'name' => 'Запуск в помещении',  'value' => $objWorksheet->getCellByColumnAndRow(15, $i)->getValue());
						//$params[]= array('name' => 'Видео',  'value' => $objWorksheet->getCellByColumnAndRow(17, $i)->getValue());
						$params[]= array('attrbute_id'=> 16, 'name' => 'Видео',  'value' => $objWorksheet->getCellByColumnAndRow(17, $i)->getValue());
						$params[]= array('attrbute_id'=> 18, 'name' => 'Эффекты',  'value' => $objWorksheet->getCellByColumnAndRow(18, $i)->getValue());
						$params[]= array('attrbute_id'=> 19, 'name' => 'Мощность',  'value' => $objWorksheet->getCellByColumnAndRow(20, $i)->getValue());
						$params[]= array('attrbute_id'=> 12, 'name' => 'Заряды',  'value' => $objWorksheet->getCellByColumnAndRow(21, $i)->getValue());
						$params[]= array('attrbute_id'=> 13, 'name' => 'Калибр',  'value' => $objWorksheet->getCellByColumnAndRow(22, $i)->getValue());
						$params[]= array('attrbute_id'=> 14, 'name' => 'Время работы',  'value' => $objWorksheet->getCellByColumnAndRow(23, $i)->getValue());
						$params[]= array('attrbute_id'=> 20, 'name' => 'Безопасный радиус',  'value' => $objWorksheet->getCellByColumnAndRow(24, $i)->getValue());
						$params[]= array('attrbute_id'=> 21, 'name' => 'Вес',  'value' => $objWorksheet->getCellByColumnAndRow(25, $i)->getValue());
						$params[]= array('attrbute_id'=> 15, 'name' => 'Высота подъема',  'value' => $objWorksheet->getCellByColumnAndRow(26, $i)->getValue());
						$params[]= array('attrbute_id'=> 22, 'name' => 'Габариты',  'value' => $objWorksheet->getCellByColumnAndRow(27, $i)->getValue());
						$params[]= array('attrbute_id'=> 23, 'name' => 'Фасовка',  'value' => $objWorksheet->getCellByColumnAndRow(34, $i)->getValue());


						$cat_lv2 = $objWorksheet->getCellByColumnAndRow(36, $i)->getValue();

						// $search_prod = array_search($artikul, array_column($products, 'name'));
						// if($search!==false){
						// 	//$data['id'] = $all_categoryes[$search]['id'];
						// 	$categoryId = $all_categoryes[$search]['cat_id'];
						// 	//$this->model_user_price_upload_vendor->updateVendorCategory($data);
						// }


		if(!isset($products[$artikul])){
				$products[$artikul] = [
					'artikul' => $artikul,
					'active' => $active,
					'name' => $name,
					'image' => $image,
					'image_dop' => $image_dop,
					'priviewText' => $priviewText,
					'detailText' => $datailText,
					'code' => $code,
					'category' => [],
					'params' => $params,
				];
		}
/*
		if(!in_array($cat_lv1, $products[$artikul]['category']) && $cat_lv1){
			$products[$artikul]['category'][]=$cat_lv1;
		}
		if(!in_array($cat_lv2, $products[$artikul]['category']) && $cat_lv2){
			$products[$artikul]['category'][]=$cat_lv2;
		}
*/
		$categoryLv1Id = $categoryLv2Id = 0;
		$search_cat = array_search($cat_lv1, array_column($all_categoryes, 'name'));
		if($search_cat!==false){
			//$data['id'] = $all_categoryes[$search]['id'];
			$categoryLv1Id = $all_categoryes[$search_cat]['category_id'];
			//$this->model_user_price_upload_vendor->updateVendorCategory($data);
		}else{
				$categoryLv1Id = $this->model_user_price_upload_vendor->addCategory(
				array(
				'parent_id' => 74, //Добавляем в Наборы
				'top' => 1,
				'sort_order' => 0,
				'column' => 1,
				'sort' => 0,
				'image' => '',
				'status' => 1,
				'category_description' => array('2'=> array('name'=>$cat_lv1,
																'meta_title'=>$cat_lv1,
																'description'=>'',
																'meta_description'=>'',
																'meta_keyword'=>'',
															),
												),
				));

				$all_categoryes[]= ['name'=>$cat_lv1, 'category_id'=>$categoryLv1Id, 'parent_id'=>0];
		}

		if($cat_lv2){
			$search_catLv2 = false;
			foreach($all_categoryes as $c){
				if($c['name'] == $cat_lv2){
					if($c['parent_id'] == $categoryLv1Id){
						$search_catLv2 = $c['category_id'];
						break;
					}
				}
			}

			if($search_catLv2){
				$categoryLv2Id = $search_catLv2;
			}else{
					$categoryLv2Id = $this->model_user_price_upload_vendor->addCategory(
					array(
					'parent_id' => $categoryLv1Id,
					'top' => 1,
					'sort_order' => 0,
					'column' => 1,
					'sort' => 0,
					'image' => '',
					'status' => 1,
					'category_description' => array('2'=> array('name'=>$cat_lv2,
																	'meta_title'=>$cat_lv2,
																	'description'=>'',
																	'meta_description'=>'',
																	'meta_keyword'=>'',
																),
													),
					));
					$all_categoryes[]= ['name'=>$cat_lv2, 'category_id'=>$categoryLv2Id, 'parent_id'=>$categoryLv1Id];
			}

		}
			if(!in_array($categoryLv1Id, $products[$artikul]['category']) && $categoryLv1Id){
				$products[$artikul]['category'][]=$categoryLv1Id;
			}
			if(!in_array($categoryLv2Id, $products[$artikul]['category']) && $categoryLv2Id){
				$products[$artikul]['category'][]=$categoryLv2Id;
			}




			// $search_catLv2 = array_search($cat_lv2, array_column($all_categoryes, 'name'));
			// $search_catLv2_parent = array_search($categoryLv1Id, array_column($all_categoryes, 'parent_id'));
			// if($search_cat!==false && $search_catLv2_parent!==false){
			// 	//$data['id'] = $all_categoryes[$search]['id'];
			//
			// 	$categoryLv2Id = $all_categoryes[$search_cat]['category_id'];
			// 	//$this->model_user_price_upload_vendor->updateVendorCategory($data);
			// }

				}
				
			echo $i.'--END 222--<br>';
			$this->cache->set('piro_products', $products);

			//	print_r($products);
			echo count($products);
			//			echo '<pre>';	echo '</pre>';
			//print_r($products['D99-08352']);
			//exit;
foreach($products as $p){
	$search_product = array_search($p['name'], array_column($all_products, 'name'));
 	if($search_product!==false ){

			echo $p['artikul'].'__edit__'.$p['active'].'<br>';

			echo '<br>';
			$product_id = $all_products[$search_product]['product_id'];
			$active = ($p['active'] == 'Y') ? 1 : 0;
			echo $active.'_edit<br>';
		$this->model_user_price_upload_vendor->editProduct_Import($product_id, array(
			'model' => $p['name'],
			'sku' => $p['artikul'],
			'upc' => '',
			'ean' =>'',
			'jan' => '',
			'category' => $p['category'][0],
			'mpn' => '',
			'isbn' => '',
			'location' => '',
			'quantity' =>  66,
			'minimum' => 1,
			'subtract' => 1,
			'stock_status_id' => 7,  //Присутствие на складе
			'date_available' => date("Y.m.d"),
			'manufacturer_id' => 0,
			'shipping' => 1,
			'price' => 0,
			'product_special' => 0,
			'points' => 0,
			'weight' => 0,
			'weight_class_id' => 1,
			'length' => 0,
			'width' => 0,
			'height' => 0,
			'length_class_id' => 1,
			'status' => $active,
			'product_store' => array(0),
			'tax_class_id' => 9,
			'sort_order' => 0,
			'image' => 'catalog/products'.$p['image'],
			'images' => array('catalog/products'.$p['image_dop']),
			'product_category' => $p['category'],
			'product_description' => array('2'=>array(
											'name' => $p['name'],
											'description' => $p['detailText'],
											'keyword' => '',
											'tag' => '',
											'meta_title' => $p['name'],
											'meta_description' => $p['priviewText'],
											'meta_keyword' => '',
										)),


		));
		$this->model_user_price_upload_vendor->editProduct_Attribute($product_id, $p['params']);

	}else{
		echo $p['artikul'].'__Add<br>';
		$active = ($p['active'] == 'Y') ? 1 : 0;
		echo $active.'_add_'.$p['active'].'<br>';

		$this->model_user_price_upload_vendor->addProduct_Import(array(
									'model' => $p['name'],
									'sku' => $p['artikul'],
									'upc' => '',
									'ean' =>'',
									'jan' => '',
									'category' => $p['category'][0],
									'mpn' => '',
									'isbn' => '',
									'location' => '',
									'quantity' =>  66,
									'minimum' => 1,
									'subtract' => 1,
									'stock_status_id' => 7,  //Присутствие на складе
									'date_available' => date("Y.m.d"),
									'manufacturer_id' => 0,
									'shipping' => 1,
									'price' => 0,
									'product_special' => 0,
									'points' => 0,
									'weight' => 0,
									'weight_class_id' => 1,
									'length' => 0,
									'width' => 0,
									'height' => 0,
									'length_class_id' => 1,
									'status' => $active,
									'product_store' => array(0),
									'tax_class_id' => 9,
									'sort_order' => 0,
									'image' => 'catalog/products'.$p['image'],
									'images' => array('catalog/products'.$p['image_dop']),
									'product_category' => $p['category'],
									'product_description' => array('2'=>array(
																	'name' => $p['name'],
																	'description' => $p['detailText'],
																	'keyword' => '',
																	'tag' => '',
																	'meta_title' => $p['name'],
																	'meta_description' => $p['priviewText'],
																	'meta_keyword' => '',
																)),


								));
	}
}

				//	$this->response->setOutput(json_encode($json)); getSiteProducts_group_by_article
			//	$this->response->redirect($this->url->link('user_price/upload/asabella', 'token=' . $this->session->data['token'], 'SSL'));

	}


	public function import_products2() {
		
		echo '<meta charset="utf-8">';
		
		$this->load->model('user_price/upload_vendor');
		include_once('simple_html_dom.php');
		$xml = simplexml_load_file('http://pironet.ru/upload/goods.xml');
		$all_products = $all_customer_groups = [];
		$all_products_t = $this->model_user_price_upload_vendor->getSiteProducts();
		$all_site_categoryes_t = $this->model_user_price_upload_vendor->getSiteCategoryes();
		$all_site_categoryes = [];
	//	$this->model_user_price_upload_vendor->disableSiteProducts(); 
		$products = [];

 
		foreach ($xml->catalog->categories->category as $c) {
			$search = array_search((string)$c->name, array_column($all_site_categoryes_t, 'name'));
			if($search!==false){
				$all_site_categoryes[(string)$c->id] = $all_site_categoryes_t[$search];
			}	
				//echo (int)$c->id.'___'.(string)$c->parent_id.'___'.(string)$c->name.'<br>';
		}

		foreach ($xml->catalog->goods->item as $i) {
						
			$params = $filters =[];
			$name = (string)$i->name;
			$active = 'Y';
			$novinka= '';
			$recomended= '';
			$priviewText = (string)$i->description;
			$datailText =  (string)$i->description;
			$location =  (string)$i->country;
			$code =  (string)$i->articul;
			$Dimensions =  (string)$i->Dimensions;
			$Weight =  (string)$i->Weight;
			$artikul =  (string)$i->id;
			$category =  (string)$i->category;
			$image = (string)$i->images->image;
			
			$path = 'catalog/products/upload/store';
			if($image){
				$pathinfo = pathinfo($image);
				$img_name = $pathinfo['basename'];
				//print_r($matches);
				$url = $image;
				if($img_name){
					if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/image/'.$path.'/'.$img_name)){
						
						try {
							$f = file_get_contents($url);
						}
						catch (Exception $e) {
							$f = false;
						}
						if($f!==FALSE) file_put_contents($_SERVER['DOCUMENT_ROOT'].'/image/'.$path.'/'.$img_name, $f); 
						else $img_name = '';
					}	
					if($img_name)
						$image = $path.'/'.$img_name;
					else $image = '';
				}	
				
			}else $image = ''; //$image = 'catalog/products/'.$vendor.'/noimage.png';
			



			$video = (string)$i->videos->video;
			if( !empty($video) )
				$params[]= array('attrbute_id'=> 16, 'name' => 'Видео',  'value' => $video);
			if( !empty($video) )
				$params[]= array('attrbute_id'=> 21, 'name' => 'Вес',  'value' => $Weight);
			if( !empty($video) )
				$params[]= array('attrbute_id'=> 26, 'name' => 'Размер',  'value' => $Dimensions);
			
			foreach ($i->properties->property as  $p){
				
				switch($p->name) { 
					case 'Новинка':
						$novinka = $p->value;
					break;
					case 'Рекомендовано':
						$recomended = $p->value;
					break;
					case 'Время работы':
						$params[14]= array('attrbute_id'=> 14, 'name' => 'Время работы',  'value' => (string)$p->value);
					break;
					case 'Высота подъема':
						$params[15]= array('attrbute_id'=> 15, 'name' => 'Высота подъема',  'value' => (string)$p->value);
					break;					
					case 'Безопасный радиус':
						$params[20]= array('attrbute_id'=> 20, 'name' => 'Безопасный радиус',  'value' => (string)$p->value);
					break;						
					case 'Торговая марка':
						$params[24]= array('attrbute_id'=> 24, 'name' => 'Торговая марка',  'value' => (string)$p->value);
					break;						
					case 'Назначение':
						$params[25]= array('attrbute_id'=> 25, 'name' => 'Назначение',  'value' => (string)$p->value);
					break;						
					case 'Количество залпов':
						$params[12]= array('attrbute_id'=> 12, 'name' => 'Количество залпов',  'value' => (string)$p->value);
					break;		
					case 'Фасовка':
						$params[23]= array('attrbute_id'=> 23, 'name' => 'Фасовка',  'value' => (string)$p->value);
					break;	
					case 'Калибр':
						/*if(isset($params[13])){
							$params[13]['value'].=','.(string)$p->value;
						}else
							*/
						$params[13]= array('attrbute_id'=> 13, 'name' => 'Калибр',  'value' => (string)$p->value);
					break;	
					case 'Эффект': 
						if(isset($params[18])){
							$params[18]['value'].=','.(string)$p->value;
						}else
						$params[18]= array('attrbute_id'=> 18, 'name' => 'Эффект',  'value' => (string)$p->value);
					break;
					
			
				}
				
			}
			$icon = '';
			if($novinka == 'true' && $recomended=='true'){
				$icon = 'a:2:{i:1;s:1:"1";i:2;s:1:"2";}';
			}else if($novinka == 'true'){
				$icon = 'a:1:{i:1;s:1:"1";}';
			}else if($recomended == 'true'){
				$icon = 'a:1:{i:2;s:1:"2";}';
			}


		if( !isset($products[$artikul]) ){ 
			$products[$artikul] = [
				'icon' => $icon,
				'artikul' => $artikul,
				'novinka' => $novinka,
				'recomended' => $recomended,
				'active' => $active,
				'name' => $name,
				'category' => $category,
				'image' => $image,
				//'image_dop' => $image_dop,
				'priviewText' => $priviewText,
				'detailText' => $datailText,
				'code' => $code,
				//'category' => [],
				'params' => $params,
				'filters' => $filters,
			];
				
		}	

	}

				foreach($products as $p){
					$search_product = array_search($p['artikul'], array_column($all_products_t, 'sku'));
					if($search_product!==false ){

//echo $p['artikul'].'__edit__'.$p['active'].'__'.$p['novinka'].'__'.$p['recomended'].'__'.$p['icon'].'__<br>';


						$product_id = $all_products_t[$search_product]['product_id'];
						$active = ($p['active'] == 'Y') ? 1 : 0;
					 	$this->model_user_price_upload_vendor->editProduct_Import($product_id, array(
							'model' => $p['name'],
							'sku' => $p['artikul'], 
							'icon' => $p['icon'], 
							'upc' => '',
							'ean' =>'',
							'jan' => '',
							//'category' => $p['category'][0],
							'mpn' => '',
							'isbn' => '',
							'location' => '',
							'quantity' =>  66,
							'minimum' => 1,
							'subtract' => 1,
							'stock_status_id' => 7,  //Присутствие на складе
							'date_available' => date("Y.m.d"),
							'manufacturer_id' => 0,
							'shipping' => 1,
							'price' => 0,
							'product_special' => 0,
							'points' => 0,
							'weight' => 0,
							'weight_class_id' => 1,
							'length' => 0,
							'width' => 0,
							'height' => 0,
							'length_class_id' => 1,
							'status' => $active,
							'product_store' => array(0),
							'tax_class_id' => 9,
							'sort_order' => 0,
							'image' => $p['image'],
							//'images' => array('catalog/products'.$p['image_dop']),
							//'product_category' => $p['category'],
							'product_description' => array('2'=>array(
															'name' => $p['name'],
															'description' => $p['detailText'],
															'keyword' => '',
															'tag' => '',
															'meta_title' => $p['name'],
															'meta_description' => $p['priviewText'],
															'meta_keyword' => '',
														)),


						)); 
						$this->model_user_price_upload_vendor->editProduct_Attribute($product_id, $p['params']);

					}else{
//echo $p['artikul'].'__Add<br>';
						if(!empty($all_site_categoryes[$p['category']])) $cat = $all_site_categoryes[$p['category']];
						else $cat = [];
						//print_r($all_site_categoryes[$p['category']]);
						$active = ($p['active'] == 'Y') ? 1 : 0;
						//echo $active.'_add_'.$p['active'].'<br>';

						 $this->model_user_price_upload_vendor->addProduct_Import(array(
													'model' => $p['name'],
													'sku' => $p['artikul'],
													'category' => $cat,
													'icon' => $p['icon'], 
													'upc' => '',
													'ean' =>'',
													'jan' => '',
													'keyword' => $this->getTranslit($p['name']),
													'mpn' => '',
													'isbn' => '',
													'location' => '',
													'quantity' =>  66,
													'minimum' => 1,
													'subtract' => 1,
													'stock_status_id' => 7,  //Присутствие на складе
													'date_available' => date("Y.m.d"),
													'manufacturer_id' => 0,
													'shipping' => 1,
													'price' => 0,
													'product_special' => 0,
													'points' => 0,
													'weight' => 0,
													'weight_class_id' => 1,
													'length' => 0,
													'width' => 0,
													'height' => 0,
													'length_class_id' => 1,
													'status' => $active,
													'product_store' => array(0),
													'tax_class_id' => 9,
													'sort_order' => 0,
													'image' => $p['image'],
													//'images' => array('catalog/products'.$p['image_dop']),
													//'product_category' => $p['category'],
													'product_description' => array('2'=>array(
																					'name' => $p['name'],
																					'description' => $p['detailText'],
																					'keyword' => '',
																					'tag' => '',
																					'meta_title' => $p['name'],
																					'meta_description' => $p['priviewText'],
																					'meta_keyword' => '',
																				)),


												));  

					}
				}
	echo '<br>END Products<br>';			
			//Информируем	
	//		mail("gorely.aleksei@yandex.ru", "cron import_products", "import_products\n");
	}

	public function import_products() {

		echo '<meta charset="utf-8">';
		
		$this->load->model('user_price/upload_vendor');
		$all_products = $all_customer_groups = [];
		//$all_products_t = $this->model_user_price_upload_vendor->getSiteProducts();

		
		include_once('simple_html_dom.php');
		$xml = simplexml_load_file('http://alexgo3j.bget.ru/upload/goods.xml');
		$importFile = $_SERVER['DOCUMENT_ROOT'].'/upload/goods.xml';
		$fileDate = strtotime(date("YmdHis",filemtime($importFile)));
		
		$all_products = $all_customer_groups = [];
		$all_products_t = $this->model_user_price_upload_vendor->getSiteProducts();
		$all_site_categoryes_t = $this->model_user_price_upload_vendor->getSiteCategoryes();
		$all_site_categoryes = [];
	//	$this->model_user_price_upload_vendor->disableSiteProducts(); 
		$products = [];
 /*
  echo '<pre>';
print_r($all_site_categoryes_t);
 //print_r($xml);
 echo '</pre>';
 */
		$config_language_id = (int)$this->config->get('config_language_id');
		foreach ($xml->categories->category as $c) {
			if(!empty($c->images->image)){
				$category_image = 'catalog/img/'.(string)$c->images->image;
			}else{
				$category_image = 'catalog/noimage.png';
			}
			$category_name = (string)$c->name;
			$category_origin_id = (string)$c->id;
			//echo $c->name;
			//Ищем категорию на сайте по id
			$search = array_search($category_origin_id, array_column($all_site_categoryes_t, 'origin_id'));
			if($search!==false){
				$all_site_categoryes[$category_origin_id] = $all_site_categoryes_t[$search];
				
				//Проверяем есть ли изменения в инфе по категориям (если есть, то обновляем)
				if(($category_name != $all_site_categoryes_t[$search]['name']) || ((string)$c->images->image != $all_site_categoryes_t[$search]['image'])){
					
					echo $category_origin_id.'  NeedModified<br>';
					
					$parent_id = $all_site_categoryes_t[$search]['parent_id'];
					$new_cat_id = $this->model_user_price_upload_vendor->editCategory(
						$all_site_categoryes_t[$search]['category_id'],
						array( 
							//'category_id' => $all_site_categoryes_t[$search]['category_id'],
							'parent_id' => $parent_id,
							'top' => 0,
							'sort_order' => 0,
							'column' => 0,
							'sort' => 0,
							'image' => $image,
							'status' => 1,
							'category_store' => array(0),
							'category_description' => array($config_language_id => array('name'=>$category_name,
																		'meta_title'=>$category_name,
																		'meta_h1'=>$category_name,
																		'description'=>$category_name,
																		'meta_description'=>'',
																		'meta_keyword'=>'',
																	),
														),
							//'category_seo_url' => array(), 
						)
					);
				}
				
				
			}	
			else {//Категория не найдена -> Добавляем 
				
				$parent_id = 0;
				if($c->parent_id)
					if(isset($all_site_categoryes[(string)$c->parent_id])){
						$parent_id = $all_site_categoryes[(string)$c->parent_id]['category_id'];
					}else {
						echo '<br>NoParent!!!!<br>'; 
						continue;
					}
				$image = (string)$c->images->image;
				if($image) $image = 'catalog/products/'.$image;
				else  $image = 'catalog/noimage.png';
				//echo $c->id.'____'.$parent_id.'<br>';	
				$new_cat_id = $this->model_user_price_upload_vendor->addCategory(
					array( 
					'parent_id' => $parent_id,
					'origin_id' => $category_origin_id,
					'top' => 0,
					'sort_order' => 0,
					'column' => 0,
					'sort' => 0,
					'image' => $image,
					'category_store' => array(0),
					'status' => 1,
					'category_description' => array($config_language_id => array('name'=>$category_name,
																'meta_title'=>$category_name,
																'meta_h1'=>$category_name,
																'description'=>$category_name,
																'meta_description'=>'',
																'meta_keyword'=>'',
															),
					),
					//'category_seo_url' => array(), 
					));
				echo 	'<br>Category '.$new_cat_id.' Added!!!!<br>'; 
				$all_site_categoryes[$category_origin_id] = array(
					'name'=> $category_name,
					'parent_id' => $parent_id,
					'category_id' => $new_cat_id,
				);
			}
				//echo (int)$c->id.'___'.(string)$c->parent_id.'___'.(string)$c->name.'<br>';
		}
/* 
 echo '<pre>';
	print_r($all_site_categoryes);
 echo '</pre>';
 */

		foreach ($xml->goods->item as $i) {
						
			$params = $filters =[];
			$name = (string)$i->name;
			
			$novinka= 0;
			$xit= 0;
			$priviewText = (string)$i->description;
			$datailText =  (string)$i->description;
			//$location =  (string)$i->country;
			$artikul =  (string)$i->articul;
			$m_code =  (string)$i->m_code;
			//$Weight =  (string)$i->Weight;
			//$artikul =  (string)$i->id;
			$category =  (string)$i->category;
			$image = (string)$i->images->image;
			if($image) $image = 'catalog/products/'.$image;
			else  $image = 'catalog/noimage.png';
			$quantity = (int)$i->catalog_quantity; 
			$active = $quantity ? 'Y' : 'N';
			$p = [
				//'icon' => $icon,
				'artikul' => $artikul,
				'm_code' => $m_code,
				'novinka' => $novinka,
				'xit' => $xit,
				'active' => $active,
				'name' => $name,
				'category' => $category,
				'image' => $image,
				'quantity' => $quantity,
				//'image_dop' => $image_dop,
				'priviewText' => $priviewText,
				'detailText' => $datailText,
				//'code' => $code,
				//'category' => [],
				//'params' => $params,
				//'filters' => $filters,
			];


/*

			<goods_type>875b2bb9-7231-11e5-b895-902b3434a4df</goods_type>
			<id>69394015-e01c-11e3-be4d-902b3434a4df</id>
			<name>Пошипник игольчатый c внутренней обоймой NK152716 19x27x16мм</name>
			<articul>9608-NK152716</articul>
			<category>875b2bb9-7231-11e5-b895-902b3434a4df</category>
			<m_code>LU049927</m_code>
			<n_group>28</n_group>
			<catalog_quantity>1</catalog_quantity>
			<total_quantity>2</total_quantity>
			<images>
				<image>t00000018719.jpg</image>
			</images>
			
			
*/				
				if(!empty($all_site_categoryes[$p['category']])) $cat = $all_site_categoryes[$p['category']];
				else $cat = [];
				$search_product = array_search($p['artikul'], array_column($all_products_t, 'sku'));
					if($search_product!==false ){

//echo $p['artikul'].'__edit__'.$p['active'].'__'.$p['novinka'].'__'.$p['recomended'].'__'.$p['icon'].'__<br>';


						$product_id = $all_products_t[$search_product]['product_id'];
						$active = ($p['active'] == 'Y') ? 1 : 0;
					 	$this->model_user_price_upload_vendor->editProduct_Import($product_id, array(
							'model' => $p['name'],
							'category' => $cat,
							'sku' => $p['artikul'], 
							//'icon' => $p['icon'], 
							'upc' => $p['m_code'], 
							'novinka' => $novinka,
							'xit' => $xit,
							'ean' =>'',
							'jan' => '',
							//'category' => $p['category'][0],
							'mpn' => '',
							'isbn' => '',
							'location' => '',
							'quantity' =>  $p['quantity'],
							'minimum' => 1,
							'subtract' => 1,
							'stock_status_id' => $p['quantity'] ? 7 : 5,  //Присутствие на складе  7 - есть на складе, 5 нет в наличии, 8-предзаказ
							'date_available' => date("Y.m.d"),
							'manufacturer_id' => 0,
							'shipping' => 1,
							'price' => 0,
							'product_special' => 0,
							'points' => 0,
							'weight' => 0,
							'weight_class_id' => 1,
							'length' => 0,
							'width' => 0,
							'height' => 0,
							'length_class_id' => 1,
							'status' => $active,
							'product_store' => array(0),
							'tax_class_id' => 9,
							'sort_order' => 0,
							'image' => $p['image'],
							//'images' => array('catalog/products'.$p['image_dop']),
							//'product_category' => $p['category'],
							'product_description' => array('2'=>array(
															'name' => $p['name'],
															'description' => $p['detailText'],
															'keyword' => '',
															'tag' => '',
															'meta_title' => $p['name'],
															'meta_description' => $p['priviewText'],
															'meta_keyword' => '',
														)),
							//'product_seo_url' => array(),			

						)); 
						//$this->model_user_price_upload_vendor->editProduct_Attribute($product_id, $p['params']);

					}else{
//echo $p['artikul'].'__Add<br>';
						
						//print_r($all_site_categoryes[$p['category']]);
						$active = ($p['active'] == 'Y') ? 1 : 0;
						//echo $active.'_add_'.$p['active'].'<br>';

						 $this->model_user_price_upload_vendor->addProduct_Import(array(
													'model' => $p['name'],
													'sku' => $p['artikul'],
													'category' => $cat,
													//'icon' => $p['icon'], 
													'upc' => $p['m_code'], 
													'novinka' => $novinka,
													'xit' => $xit,
													'ean' =>'',
													'jan' => '',
													'keyword' => $this->getTranslit($p['name']),
													'mpn' => '',
													'isbn' => '',
													'location' => '',
													'quantity' =>  $p['quantity'],
													'minimum' => 1,
													'subtract' => 1,
													'stock_status_id' => $p['quantity'] ? 7 : 5,  //Присутствие на складе  7 - есть на складе, 5 нет в наличии, 8-предзаказ
													'date_available' => date("Y.m.d"),
													'manufacturer_id' => 0,
													'shipping' => 1,
													'price' => 0,
													'product_special' => 0,
													'points' => 0,
													'weight' => 0,
													'weight_class_id' => 1,
													'length' => 0,
													'width' => 0,
													'height' => 0,
													'length_class_id' => 1,
													'status' => $active,
													'product_store' => array(0),
													'tax_class_id' => 9,
													'sort_order' => 0,
													'image' => $p['image'],
													//'images' => array('catalog/products'.$p['image_dop']),
													//'product_category' => $p['category'],
													'product_description' => array('2'=>array(
																					'name' => $p['name'],
																					'description' => $p['detailText'],
																					'keyword' => '',
																					'tag' => '',
																					'meta_title' => $p['name'],
																					'meta_description' => $p['priviewText'],
																					'meta_keyword' => '',
																				)),
													//'product_seo_url' => array(),							


												));  

					}
// print_r($p);
// exit;
			
	}


//Информируем	
//mail("gorely.aleksei@yandex.ru", "cron import_price", "import_price\n");


echo '<br>end price';
exit;
/**/
				$upl_prod = array();
				set_time_limit(120);
				$this->load->model('user_price/upload_vendor');
				//include_once('simple_html_dom.php');
				$a = false;
				$i = 0;
				$limit = 300;
				$json = array();


				$cikle_break = true;
				$a = false;
				$params = array();
				$inf = $inf2 = $inf_3 = '';
				$all_categoryes = $this->model_user_price_upload_vendor->getSiteCategoryes();
				$all_products = $this->model_user_price_upload_vendor->getSiteProducts();
				
				//$all_manufacturers = $this->model_user_price_upload_vendor->getManufacturers();
				//	$path = $_SERVER["DOCUMENT_ROOT"].'/image/catalog/products/'.$vendor;
				//$dir = $this->request->server['DOCUMENT_ROOT'].'/image/catalog/products/'.$vendor.'/';
				// print_r($all_products);


				$file_name = $_SERVER['DOCUMENT_ROOT'].'/upload/pironet.xlsx';

				set_include_path(get_include_path() . PATH_SEPARATOR .$_SERVER['DOCUMENT_ROOT'].'/admin/model/phpExel/Classes/');
				include_once 'PHPExcel.php';
				include("PHPExcel/Writer/Excel5.php");

			//	$all_vendor_site_products = $this->model_user_price_upload_vendor->getVendorProducts($vendor);
			//	$all_vendor_site_products = $this->model_user_price_upload_vendor->getVendorProducts_onSite($vendor);
			//	print_r($all_vendor_site_products);

				$xls = PHPExcel_IOFactory::load($file_name);
				$xls->setActiveSheetIndex(0);
				$objWorksheet = $xls->getActiveSheet();

				$chunkSize = 10000;      //размер считываемых строк за раз
				$startRow  = 20000;
				$empty_value = 0; 

				$products = []; 

			$products = $this->cache->get('piro_products');
				if(!count($products))
				 for ($i = $startRow; $i <= $startRow + $chunkSize; $i++)  //внутренний цикл по строкам 
					{

						$params = [];
						$artikul = $objWorksheet->getCellByColumnAndRow(0, $i)->getValue();
						$cat_lv1 = $objWorksheet->getCellByColumnAndRow(35, $i)->getValue();

						if(!$artikul){
							echo $i.'--END--<br>';
							break;
						}
						if(!$cat_lv1){
							continue;
						}

						$name = $objWorksheet->getCellByColumnAndRow(1, $i)->getValue();
						$active = $objWorksheet->getCellByColumnAndRow(3, $i)->getValue();
						$image = $objWorksheet->getCellByColumnAndRow(6, $i)->getValue();
						if($image) $image = 'catalog/products'.$image;
						$image_dop = $objWorksheet->getCellByColumnAndRow(9, $i)->getValue();
						if($image_dop) $image_dop = 'catalog/products'.$image_dop;
						$priviewText = $objWorksheet->getCellByColumnAndRow(7, $i)->getValue();
						$datailText = $objWorksheet->getCellByColumnAndRow(10, $i)->getValue();
						$code = $objWorksheet->getCellByColumnAndRow(12, $i)->getValue();

						$params[]= array('attrbute_id'=> 17, 'name' => 'Запуск в помещении',  'value' => $objWorksheet->getCellByColumnAndRow(15, $i)->getValue());
						//$params[]= array('name' => 'Видео',  'value' => $objWorksheet->getCellByColumnAndRow(17, $i)->getValue());
						$params[]= array('attrbute_id'=> 16, 'name' => 'Видео',  'value' => $objWorksheet->getCellByColumnAndRow(17, $i)->getValue());
						$params[]= array('attrbute_id'=> 18, 'name' => 'Эффекты',  'value' => $objWorksheet->getCellByColumnAndRow(18, $i)->getValue());
						$params[]= array('attrbute_id'=> 19, 'name' => 'Мощность',  'value' => $objWorksheet->getCellByColumnAndRow(20, $i)->getValue());
						$params[]= array('attrbute_id'=> 12, 'name' => 'Заряды',  'value' => $objWorksheet->getCellByColumnAndRow(21, $i)->getValue());
						$params[]= array('attrbute_id'=> 13, 'name' => 'Калибр',  'value' => $objWorksheet->getCellByColumnAndRow(22, $i)->getValue());
						$params[]= array('attrbute_id'=> 14, 'name' => 'Время работы',  'value' => $objWorksheet->getCellByColumnAndRow(23, $i)->getValue());
						$params[]= array('attrbute_id'=> 20, 'name' => 'Безопасный радиус',  'value' => $objWorksheet->getCellByColumnAndRow(24, $i)->getValue());
						$params[]= array('attrbute_id'=> 21, 'name' => 'Вес',  'value' => $objWorksheet->getCellByColumnAndRow(25, $i)->getValue());
						$params[]= array('attrbute_id'=> 15, 'name' => 'Высота подъема',  'value' => $objWorksheet->getCellByColumnAndRow(26, $i)->getValue());
						$params[]= array('attrbute_id'=> 22, 'name' => 'Габариты',  'value' => $objWorksheet->getCellByColumnAndRow(27, $i)->getValue());
						$params[]= array('attrbute_id'=> 23, 'name' => 'Фасовка',  'value' => $objWorksheet->getCellByColumnAndRow(34, $i)->getValue());


						$cat_lv2 = $objWorksheet->getCellByColumnAndRow(36, $i)->getValue();

						// $search_prod = array_search($artikul, array_column($products, 'name'));
						// if($search!==false){
						// 	//$data['id'] = $all_categoryes[$search]['id'];
						// 	$categoryId = $all_categoryes[$search]['cat_id'];
						// 	//$this->model_user_price_upload_vendor->updateVendorCategory($data);
						// }


		if(!isset($products[$artikul])){
				$products[$artikul] = [
					'artikul' => $artikul,
					'active' => $active,
					'name' => $name,
					'image' => $image,
					'image_dop' => $image_dop,
					'priviewText' => $priviewText,
					'detailText' => $datailText,
					'code' => $code,
					'category' => [],
					'params' => $params,
				];
		}
/*
		if(!in_array($cat_lv1, $products[$artikul]['category']) && $cat_lv1){
			$products[$artikul]['category'][]=$cat_lv1;
		}
		if(!in_array($cat_lv2, $products[$artikul]['category']) && $cat_lv2){
			$products[$artikul]['category'][]=$cat_lv2;
		}
*/
		$categoryLv1Id = $categoryLv2Id = 0;
		$search_cat = array_search($cat_lv1, array_column($all_categoryes, 'name'));
		if($search_cat!==false){
			//$data['id'] = $all_categoryes[$search]['id'];
			$categoryLv1Id = $all_categoryes[$search_cat]['category_id'];
			//$this->model_user_price_upload_vendor->updateVendorCategory($data);
		}else{
				$categoryLv1Id = $this->model_user_price_upload_vendor->addCategory(
				array(
				'parent_id' => 74, //Добавляем в Наборы
				'top' => 1,
				'sort_order' => 0,
				'column' => 1,
				'sort' => 0,
				'image' => '',
				'status' => 1,
				'category_description' => array('2'=> array('name'=>$cat_lv1,
																'meta_title'=>$cat_lv1,
																'description'=>'',
																'meta_description'=>'',
																'meta_keyword'=>'',
															),
												),
				));

				$all_categoryes[]= ['name'=>$cat_lv1, 'category_id'=>$categoryLv1Id, 'parent_id'=>0];
		}

		if($cat_lv2){
			$search_catLv2 = false;
			foreach($all_categoryes as $c){
				if($c['name'] == $cat_lv2){
					if($c['parent_id'] == $categoryLv1Id){
						$search_catLv2 = $c['category_id'];
						break;
					}
				}
			}

			if($search_catLv2){
				$categoryLv2Id = $search_catLv2;
			}else{
					$categoryLv2Id = $this->model_user_price_upload_vendor->addCategory(
					array(
					'parent_id' => $categoryLv1Id,
					'top' => 1,
					'sort_order' => 0,
					'column' => 1,
					'sort' => 0,
					'image' => '',
					'status' => 1,
					'category_description' => array('2'=> array('name'=>$cat_lv2,
																	'meta_title'=>$cat_lv2,
																	'description'=>'',
																	'meta_description'=>'',
																	'meta_keyword'=>'',
																),
													),
					));
					$all_categoryes[]= ['name'=>$cat_lv2, 'category_id'=>$categoryLv2Id, 'parent_id'=>$categoryLv1Id];
			}

		}
			if(!in_array($categoryLv1Id, $products[$artikul]['category']) && $categoryLv1Id){
				$products[$artikul]['category'][]=$categoryLv1Id;
			}
			if(!in_array($categoryLv2Id, $products[$artikul]['category']) && $categoryLv2Id){
				$products[$artikul]['category'][]=$categoryLv2Id;
			}




			// $search_catLv2 = array_search($cat_lv2, array_column($all_categoryes, 'name'));
			// $search_catLv2_parent = array_search($categoryLv1Id, array_column($all_categoryes, 'parent_id'));
			// if($search_cat!==false && $search_catLv2_parent!==false){
			// 	//$data['id'] = $all_categoryes[$search]['id'];
			//
			// 	$categoryLv2Id = $all_categoryes[$search_cat]['category_id'];
			// 	//$this->model_user_price_upload_vendor->updateVendorCategory($data);
			// }

				}
				
			echo $i.'--END 222--<br>';
			$this->cache->set('piro_products', $products);

			//	print_r($products);
			echo count($products);
			//			echo '<pre>';	echo '</pre>';
			//print_r($products['D99-08352']);
			//exit;
foreach($products as $p){
	$search_product = array_search($p['name'], array_column($all_products, 'name'));
 	if($search_product!==false ){

			echo $p['artikul'].'__edit__'.$p['active'].'<br>';

			echo '<br>';
			$product_id = $all_products[$search_product]['product_id'];
			$active = ($p['active'] == 'Y') ? 1 : 0;
			echo $active.'_edit<br>';
		$this->model_user_price_upload_vendor->editProduct_Import($product_id, array(
			'model' => $p['name'],
			'sku' => $p['artikul'],
			'upc' => '',
			'ean' =>'',
			'jan' => '',
			'category' => $p['category'][0],
			'mpn' => '',
			'isbn' => '',
			'location' => '',
			'quantity' =>  66,
			'minimum' => 1,
			'subtract' => 1,
			'stock_status_id' => 7,  //Присутствие на складе
			'date_available' => date("Y.m.d"),
			'manufacturer_id' => 0,
			'shipping' => 1,
			'price' => 0,
			'product_special' => 0,
			'points' => 0,
			'weight' => 0,
			'weight_class_id' => 1,
			'length' => 0,
			'width' => 0,
			'height' => 0,
			'length_class_id' => 1,
			'status' => $active,
			'product_store' => array(0),
			'tax_class_id' => 9,
			'sort_order' => 0,
			'image' => 'catalog/products'.$p['image'],
			'images' => array('catalog/products'.$p['image_dop']),
			'product_category' => $p['category'],
			'product_description' => array('2'=>array(
											'name' => $p['name'],
											'description' => $p['detailText'],
											'keyword' => '',
											'tag' => '',
											'meta_title' => $p['name'],
											'meta_description' => $p['priviewText'],
											'meta_keyword' => '',
										)),


		));
		$this->model_user_price_upload_vendor->editProduct_Attribute($product_id, $p['params']);

	}else{
		echo $p['artikul'].'__Add<br>';
		$active = ($p['active'] == 'Y') ? 1 : 0;
		echo $active.'_add_'.$p['active'].'<br>';

		$this->model_user_price_upload_vendor->addProduct_Import(array(
									'model' => $p['name'],
									'sku' => $p['artikul'],
									'upc' => '',
									'ean' =>'',
									'jan' => '',
									'category' => $p['category'][0],
									'mpn' => '',
									'isbn' => '',
									'location' => '',
									'quantity' =>  66,
									'minimum' => 1,
									'subtract' => 1,
									'stock_status_id' => 7,  //Присутствие на складе
									'date_available' => date("Y.m.d"),
									'manufacturer_id' => 0,
									'shipping' => 1,
									'price' => 0,
									'product_special' => 0,
									'points' => 0,
									'weight' => 0,
									'weight_class_id' => 1,
									'length' => 0,
									'width' => 0,
									'height' => 0,
									'length_class_id' => 1,
									'status' => $active,
									'product_store' => array(0),
									'tax_class_id' => 9,
									'sort_order' => 0,
									'image' => 'catalog/products'.$p['image'],
									'images' => array('catalog/products'.$p['image_dop']),
									'product_category' => $p['category'],
									'product_description' => array('2'=>array(
																	'name' => $p['name'],
																	'description' => $p['detailText'],
																	'keyword' => '',
																	'tag' => '',
																	'meta_title' => $p['name'],
																	'meta_description' => $p['priviewText'],
																	'meta_keyword' => '',
																)),


								));
	}
}

				//	$this->response->setOutput(json_encode($json));
			//	$this->response->redirect($this->url->link('user_price/upload/asabella', 'token=' . $this->session->data['token'], 'SSL'));

	}

	public function getTranslit($text, $translit = 'ru_en') {
	
		$RU['ru'] = array( 
			'Ё', 'Ж', 'Ц', 'Ч', 'Щ', 'Ш', 'Ы',  
			'Э', 'Ю', 'Я', 'ё', 'ж', 'ц', 'ч',  
			'ш', 'щ', 'ы', 'э', 'ю', 'я', 'А',  
			'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И',  
			'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',  
			'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ъ',  
			'Ь', 'а', 'б', 'в', 'г', 'д', 'е',  
			'з', 'и', 'й', 'к', 'л', 'м', 'н',  
			'о', 'п', 'р', 'с', 'т', 'у', 'ф',  
			'х', 'ъ', 'ь', '/'
			); 

		$EN['en'] = array( 
			"Yo", "Zh",  "Cz", "Ch", "Shh","Sh", "Y'",  
			"E'", "Yu",  "Ya", "yo", "zh", "cz", "ch",  
			"sh", "shh", "y'", "e'", "yu", "ya", "A",  
			"B" , "V" ,  "G",  "D",  "E",  "Z",  "I",  
			"J",  "K",   "L",  "M",  "N",  "O",  "P",  
			"R",  "S",   "T",  "U",  "F",  "Kh",  "''", 
			"'",  "a",   "b",  "v",  "g",  "d",  "e",  
			"z",  "i",   "j",  "k",  "l",  "m",  "n",   
			"o",  "p",   "r",  "s",  "t",  "u",  "f",   
			"h",  "''",  "'",  "-"
			); 
		if($translit == 'en_ru') { 
			$t = str_replace($EN['en'], $RU['ru'], $text);         
			$t = preg_replace('/(?<=[а-яё])Ь/u', 'ь', $t); 
			$t = preg_replace('/(?<=[а-яё])Ъ/u', 'ъ', $t); 
			} 
		else {
			$t = str_replace($RU['ru'], $EN['en'], $text);
			$t = preg_replace("/[\s]+/u", "_", $t); 
			$t = preg_replace("/[^a-z0-9_\-]/iu", "", $t); 
			$t = strtolower($t);
			}
		return $t; 
	
	}

	public function upload222() {
		$this->load->model('user_price/modified_price');
		$a = false;
		if(isset($_FILES['picewp']) && $_FILES['picewp']['error'] == 0){ // Проверяем, загрузил ли пользователь файл
			$destination_dir =  $_SERVER['DOCUMENT_ROOT'].'/upload/user-price-wp.xls'; // Директория для размещения файла
		 	move_uploaded_file($_FILES['picewp']['tmp_name'], $destination_dir ); // Перемещаем файл в желаемую директорию
			$a = 'Файл успешно загружен';$this->session->data['success_upload'] = $a;
			//echo '_File Uploaded'; // Оповещаем пользователя об успешной загрузке файла
			$this->model_user_price_modified_price->updateprice($_SERVER['DOCUMENT_ROOT'].'/upload/user-price-wp.xls');
		}else
		if(isset($_FILES['picep']) && $_FILES['picep']['error'] == 0){ // Проверяем, загрузил ли пользователь файл
			$destination_dir =  $_SERVER['DOCUMENT_ROOT'].'/upload/user-price-p.xls'; // Директория для размещения файла
			move_uploaded_file($_FILES['picep']['tmp_name'], $destination_dir ); // Перемещаем файл в желаемую директорию
			 $a = 'Файл успешно загружен';$this->session->data['success_upload'] = $a;
			 $this->model_user_price_modified_price->updateprice($_SERVER['DOCUMENT_ROOT'].'/upload/user-price-p.xls');
			//echo '_File Uploaded'; // Оповещаем пользователя об успешной загрузке файла

		}
		else {
			$this->session->data['error'] = 'Произошла ошибка';
		}


			$this->response->redirect($this->url->link('user_price/upload', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function updateprice() {
		$this->load->model('user_price/modified_price');
		$this->model_user_price_modified_price->updateprice('12345');
	}

	public function updatecategorytocategory() {
		$this->load->model('user_price/upload_vendor');

		$data = array(
			'import_cat_id' => $this->request->post['import_cat_id'],
			'site_cat_id' => $this->request->post['site_cat_id'],

		);

		//print_r($_POST);
		$this->model_user_price_upload_vendor->category_to_category($data);

		//$json['filter_data'] = $filter_data;
		$json['success'] = 1;
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function updateattributetoattribute() {
		$this->load->model('user_price/upload_vendor');

		$data = array(
			'import_attribute' => $this->request->post['import_attribute'],
			'site_attribute_id' => $this->request->post['site_attribute_id'],
			'vendor' => $this->request->post['vendor'],

		);

		//print_r($_POST);
		$this->model_user_price_upload_vendor->attribute_to_attribute($data);

		//$json['filter_data'] = $filter_data;
		$json['success'] = 1;
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function updateattributetofilter() {
		$this->load->model('user_price/upload_vendor');

		$data = array(
			'import_attribute' => $this->request->post['import_attribute'],
			'site_filter_group' => $this->request->post['site_filter_group'],
			'vendor' => $this->request->post['vendor'],

		);

		//print_r($_POST);
		$this->model_user_price_upload_vendor->attribute_to_filter($data);

		//$json['filter_data'] = $filter_data;
		$json['success'] = 1;
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function get_category_products() {
		$this->load->model('user_price/upload_vendor');
		$cat_id = $this->request->post['cat_id'];
		$vendor = $this->request->post['vendor'];


		//print_r($_POST);
		$rez = $this->model_user_price_upload_vendor->getCategoryProducts($cat_id, $vendor);

		//$json['filter_data'] = $filter_data;
		$json['success'] = 1;
		$json['rez'] = $rez;
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
