<?php
namespace Lucent\Hello\Controller\Index;

class Display extends \Magento\Framework\App\Action\Action
{
  public function __construct(\Magento\Framework\App\Action\Context $context)
  {
    return parent::__construct($context);
  }


  public function execute()
  {
		$data = array();

		$del_size_array = array();
		$del_color_array = array();
		$del_brand_array = array();

		$data['values'] = array();

		$data['values']['size'] =  array();
		$data['values']['color'] =  array();
		$data['values']['brand'] =  array();
		$data['values']['price'] =  '';
		$data['values']['category'] =  '';
		$data['values']['categoryLabel'] =  '';


		


		if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalog'){
				

				if(!isset($_SESSION['catalog']['color'])){
					$_SESSION['catalog']['color']  = array();
				}
				if(!isset($_SESSION['catalog']['brand'])){
					$_SESSION['catalog']['brand']  = array();
				}
				if(!isset($_SESSION['catalog']['size'])){
					$_SESSION['catalog']['size']  = array();
				}
				if(!isset($_SESSION['catalog']['price'])){
					$_SESSION['catalog']['price']  = '';
				}
				if(!isset($_SESSION['catalog']['category'])){
					$_SESSION['catalog']['category']  = '';
				}
				if(!isset($_SESSION['catalog']['categoryLabel'])){
					$_SESSION['catalog']['categoryLabel']  = '';
				}
		}
		if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalogsearch'){
			   
				
				if(!isset($_SESSION['catalogsearch']['color'])){
					$_SESSION['catalogsearch']['color']  = array();
				}
				if(!isset($_SESSION['catalogsearch']['brand'])){
					$_SESSION['catalogsearch']['brand']  = array();
				}
				if(!isset($_SESSION['catalogsearch']['size'])){
					$_SESSION['catalogsearch']['size']  = array();
				}
				if(!isset($_SESSION['catalogsearch']['price'])){
					$_SESSION['catalogsearch']['price']  = '';
				}
				if(!isset($_SESSION['catalogsearch']['category'])){
					$_SESSION['catalogsearch']['category']  = '';
				}
				if(!isset($_SESSION['catalogsearch']['categoryLabel'])){
					$_SESSION['catalogsearch']['categoryLabel']  = '';
				}
		}

		$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
		$categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
		$categoryHelper = $objectManager->get('\Magento\Catalog\Helper\Category');
		$categoryRepository = $objectManager->get('\Magento\Catalog\Model\CategoryRepository');
		$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();

	    $attributeCode = '';   
	    if(isset($_POST['attribute_code'])){
		    $attributeCode = $_POST['attribute_code'];             
	    }          
	    $option_id = '';             
	    if(isset($_POST['id'])){
		    $option_id = $_POST['id']; 
	    }          
		if(isset($_POST['cid']) && $_POST['cid']!=''){
			$categoryId = $_POST['cid'];				// YOUR CATEGORY ID
			$category = $categoryFactory->create()->load($categoryId);
			$categoryProducts = $category->getProductCollection()
			                             ->addAttributeToSelect('*');
			$categoryProducts->addAttributeToFilter('visibility', 4);

		}

		if(isset($_POST['query']) && $_POST['query']!=''){
			$query = $_POST['query'];	
			if($attributeCode=='category'){
				if($_POST['cid']!=''){
					$categoryId = $_POST['cid'];				// YOUR CATEGORY ID
					$category = $categoryFactory->create()->load($categoryId);
					$categoryProducts = $category->getProductCollection()
					                             ->addAttributeToSelect('*');
					                             $categoryProducts->addAttributeToFilter('visibility', 4);
				}else{
					$categoryProducts = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
		           ->addAttributeToSelect('*');
		           $categoryProducts->addAttributeToFilter('visibility', 4);
				}
	
			}else{
				$categoryProducts = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
	           ->addAttributeToSelect('*');
	           $categoryProducts->addAttributeToFilter('visibility', 4);
			}
			$categoryProducts->addAttributeToFilter('name', array('like' => '%'.$query.'%'));

		}
		if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalog'){         
			if(isset($_POST['action']) && $_POST['action']=='delete'){
				if($attributeCode=='size'){
					$del_size_array[] = $option_id;
					$_SESSION['catalog']['size'] = array_diff($_SESSION['catalog']['size'], $del_size_array);
				}
				if($attributeCode=='brand'){
					$del_brand_array[] = $option_id;
					$_SESSION['catalog']['brand'] = array_diff($_SESSION['catalog']['brand'], $del_brand_array);
				}
				if($attributeCode=='color'){
					$del_color_array[] = $option_id;
					$_SESSION['catalog']['color'] = array_diff($_SESSION['catalog']['color'], $del_color_array);
				}
				if($attributeCode=='price'){
					$_SESSION['catalog']['price'] = '';
				}
				if($attributeCode=='category'){
					$_SESSION['catalog']['category'] = '';
					$_SESSION['catalog']['categoryLabel'] = '';
				}
			}else{
				if($attributeCode=='category'){
					$_SESSION['catalog']['category'] = $option_id;
					$_SESSION['catalog']['categoryLabel'] = $_POST['label'];
				}
				if($attributeCode=='price'){
					$_SESSION['catalog']['price'] = $option_id;
				}
				if($attributeCode=='color'){
					array_push($_SESSION['catalog']['color'], $option_id);
				}
				if($attributeCode=='brand'){
					array_push($_SESSION['catalog']['brand'], $option_id);
				}
				if($attributeCode=='size'){
					array_push($_SESSION['catalog']['size'], $option_id);
				}
			}
		}
		if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalogsearch'){         
			if(isset($_POST['action']) && $_POST['action']=='delete'){
				if($attributeCode=='size'){
					$del_size_array[] = $option_id;
					$_SESSION['catalogsearch']['size'] = array_diff($_SESSION['catalogsearch']['size'], $del_size_array);
				}
				if($attributeCode=='brand'){
					$del_brand_array[] = $option_id;
					$_SESSION['catalogsearch']['brand'] = array_diff($_SESSION['catalogsearch']['brand'], $del_brand_array);
				}
				if($attributeCode=='color'){
					$del_color_array[] = $option_id;
					$_SESSION['catalogsearch']['color'] = array_diff($_SESSION['catalogsearch']['color'], $del_color_array);
				}
				if($attributeCode=='price'){
					$_SESSION['catalogsearch']['price'] = '';
				}
				if($attributeCode=='category'){
					$_SESSION['catalogsearch']['category'] = '';
					$_SESSION['catalogsearch']['categoryLabel'] = '';
				}
			}else{
				if($attributeCode=='category'){
					$_SESSION['catalogsearch']['category'] = $option_id;
					$_SESSION['catalogsearch']['categoryLabel'] = $_POST['label'];
				}
				if($attributeCode=='price'){
					$_SESSION['catalogsearch']['price'] = $option_id;
				}
				if($attributeCode=='color'){
					array_push($_SESSION['catalogsearch']['color'], $option_id);
				}
				if($attributeCode=='brand'){
					array_push($_SESSION['catalogsearch']['brand'], $option_id);
				}
				if($attributeCode=='size'){
					array_push($_SESSION['catalogsearch']['size'], $option_id);
				}
			}
		}
		


		if(isset($_POST['cid']) && $_POST['cid']!=''){
			$cat_id = $_POST['cid'];
			if(!isset($_SESSION['sessionid'])){
				$_SESSION['sessionid'] = $_POST['cid'];
			}
			if($cat_id!=$_SESSION['sessionid']){
				$_SESSION['sessionid'] = $cat_id;	
				$_SESSION['catalog']['color']  = array();
				$_SESSION['catalog']['brand']  = array();
				$_SESSION['catalog']['size']  = array();
				$_SESSION['catalog']['price']  = '';
				$_SESSION['catalog']['category']  = '';
				$_SESSION['catalog']['categoryLabel']  = '';
				
			}
		}


		$data['values']['sessionid'] = $_SESSION['sessionid'];

		if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalog'){ 
			$data['values']['color']  = array_values(array_unique($_SESSION['catalog']['color']));
			$data['values']['brand']  = array_values(array_unique($_SESSION['catalog']['brand']));
			$data['values']['size']   = array_values(array_unique($_SESSION['catalog']['size']));
			$data['values']['price']   = $_SESSION['catalog']['price'];
			$data['values']['category']   = $_SESSION['catalog']['category'];
			$data['values']['categoryLabel']   = $_SESSION['catalog']['categoryLabel'];
			$_SESSION['catalogsearch']['color']  = array();
			$_SESSION['catalogsearch']['brand']  = array();
			$_SESSION['catalogsearch']['size']  = array();
			$_SESSION['catalogsearch']['price']  = '';
			$_SESSION['catalogsearch']['category']  = '';
			$_SESSION['catalogsearch']['categoryLabel']  = '';
		}

		if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalogsearch'){ 
			$data['values']['color']  = array_values(array_unique($_SESSION['catalogsearch']['color']));
			$data['values']['brand']  = array_values(array_unique($_SESSION['catalogsearch']['brand']));
			$data['values']['size']   = array_values(array_unique($_SESSION['catalogsearch']['size']));
			$data['values']['price']   = $_SESSION['catalogsearch']['price'];
			$data['values']['category']   = $_SESSION['catalogsearch']['category'];
			$data['values']['categoryLabel']   = $_SESSION['catalogsearch']['categoryLabel'];
			$_SESSION['catalog']['color']  = array();
			$_SESSION['catalog']['brand']  = array();
			$_SESSION['catalog']['size']  = array();
			$_SESSION['catalog']['price']  = '';
			$_SESSION['catalog']['category']  = '';
			$_SESSION['catalog']['categoryLabel']  = '';
		}


		$data['values']['sizelabels'] = array();
		$size_data_values  =$data['values']['size'];
		for($k=0;$k<sizeof($size_data_values);$k++){
			$optionId = $size_data_values[$k];
			$attributeOptionSingle = $objectManager->create(\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection::class)
			                                       ->setPositionOrder('asc')
			                                       ->setIdFilter($optionId)
			                                       ->setStoreFilter()
			                                       ->load();
			  
			$optionLabel = $attributeOptionSingle->getData();
			$sizelabel  =$optionLabel[0]['default_value'];
			array_push($data['values']['sizelabels'], $sizelabel);
		}


		$data['values']['colorlabels'] = array();
		$color_data_values  =$data['values']['color'];
		for($l=0;$l<sizeof($color_data_values);$l++){
			$optionId = $color_data_values[$l];
			$attributeOptionSingle = $objectManager->create(\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection::class)
			                                       ->setPositionOrder('asc')
			                                       ->setIdFilter($optionId)
			                                       ->setStoreFilter()
			                                       ->load();
			  
			$optionLabel = $attributeOptionSingle->getData();
			$colorlabel  =$optionLabel[0]['default_value'];
			array_push($data['values']['colorlabels'], $colorlabel);
		}


		$data['values']['brandlabels'] = array();
		$brand_data_values  =$data['values']['brand'];
		for($m=0;$m<sizeof($brand_data_values);$m++){
			$optionId = $brand_data_values[$m];
			$attributeOptionSingle = $objectManager->create(\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection::class)
			                                       ->setPositionOrder('asc')
			                                       ->setIdFilter($optionId)
			                                       ->setStoreFilter()
			                                       ->load();
			  
			$optionLabel = $attributeOptionSingle->getData();
			$brandlabel  =$optionLabel[0]['default_value'];
			array_push($data['values']['brandlabels'], $brandlabel);
		}
		

		

		$total_sizes = sizeof($data['values']['size']);
		$selected_sizes = array_values($data['values']['size']);
		/*
		if($total_sizes > 0 ){
			$size_list = '';
			for($x=0;$x<$total_sizes;$x++){
				if($x==0){
					 $size_list .= $selected_sizes[$x];	
				}
				else{
					$size_list .= ','.$selected_sizes[$x];	
				}
			}
			$categoryProducts->addAttributeToFilter('size', array('in' => array($size_list)));
		}
		*/
		if($total_sizes > 0 ){
			$size_list = array();
			for($x=0;$x<$total_sizes;$x++){
				$size_list[$x]['finset'] = array($selected_sizes[$x]);
			}
			$categoryProducts->addAttributeToFilter('size',
			    $size_list 
			);
		}

		$total_brands = sizeof($data['values']['brand']);
		$selected_brands = array_values($data['values']['brand']);
		/*
		if($total_brands > 0 ){
			$brand_list = '';
			for($y=0;$y<$total_brands;$y++){
				if($y==0){
					$brand_list .= $selected_brands[$y];	

				}else{
					$brand_list .= ','.$selected_brands[$y];	
				}
			}
			$categoryProducts->addAttributeToFilter('brand', array('in' => array($brand_list)));
		}
		*/
		if($total_brands > 0 ){
			$brand_list = array();
			for($y=0;$y<$total_brands;$y++){
				$brand_list[$y]['finset'] = array($selected_brands[$y]);
			}
			$categoryProducts->addAttributeToFilter('brand',
			    $brand_list 
			);
		}


		$total_colors = sizeof($data['values']['color']);
		$selected_colors = array_values($data['values']['color']);
		/*
		if($total_colors > 0 ){
			$color_list = '';
			for($z=0;$z<$total_colors;$z++){
				if($z==0){
					$color_list .= $selected_colors[$z];	

				}else{
					$color_list .= ','.$selected_colors[$z];	
				}
			}
			$categoryProducts->addAttributeToFilter('color', array('in' => array($color_list)));
		}
		*/
		if($total_colors > 0 ){
			$color_list = array();
			for($z=0;$z<$total_colors;$z++){
				$color_list[$z]['finset'] = array($selected_colors[$z]);
			}
			$categoryProducts->addAttributeToFilter('color',
			    $color_list 
			);
		}




		//if($attributeCode=='price'){
			
			if($data['values']['price']!=''){
				$price_value = explode('-', $data['values']['price']);

				if($price_value[0] == ''){
					$price_filter = $price_value[1];	
					$filter_val = 'lt';	
					$categoryProducts->addAttributeToFilter(array(array('attribute' => 'price', $filter_val => $price_filter)));

					//$categoryProducts->addAttributeToFilter('price', array( $filter_val => $price_filter ));
				}else if($price_value[1] == ''){
					$price_filter = $price_value[0];
					$filter_val = 'gteq';	
					$categoryProducts->addAttributeToFilter('price', array( $filter_val => $price_filter ));
				}else{
					$from = $price_value[0];
					$to = $price_value[1] -1 ;
					$categoryProducts->addAttributeToFilter('price', array('from' => $from, 'to' => $to));
				}
			}
			
		//}			

		// if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalog'){
		// 	$data['catalog'] = $data;
		// 	$data['catalogsearch'] = array();
		// }
		// if(isset($_POST['filterpage']) && $_POST['filterpage']=='catalogsearch'){
		// 	$data['catalogsearch'] = $data;
		// 	$data['catalog'] = array();
		// }



		//echo $categoryProducts->getSelect()->__toString();
		//pagination data    
	    $data['pagination']  = array();
	    $limit =  4;
	    $curpage =  1;
	    if(isset($_POST['limit']) && $_POST['limit']!=''){
		    $limit =  $_POST['limit'] ;
	    }
	    if(isset($_POST['page']) && $_POST['page']!=''){
		    $curpage = $_POST['page'];
	    }

	    $total_products = $categoryProducts->getSize();
		$total_pages = ceil($total_products / $limit);
		$products_Data  = $categoryProducts->setPageSize($limit)->setCurPage($curpage);

		$pagination_data = '';
		if($total_pages > 1 ){
			if($curpage == 1){
				$pagination_data .= '<a href="javascript:;" class="pagenumber_static pager" limit="'.$limit.'" data="1">Prev<a>';
			}else{
				$prevpage = $curpage  - 1;
				$pagination_data .= '<a href="javascript:;" class="pagenumber pager" limit="'.$limit.'" data="'.$prevpage.'">Prev<a>';
			}
		}
		for($pg=1;$pg<=$total_pages;$pg++){
			if($pg == $curpage){
				$pagination_data .= '<a href="javascript:;" class="pagenumber_static pager" limit="'.$limit.'" data="'.$pg.'">'.$pg.'<a>';
			}else{
				$pagination_data .= '<a href="javascript:;" class="pagenumber  pager" limit="'.$limit.'" data="'.$pg.'">'.$pg.'<a>';
			}
		}
		if($total_pages > 1 ){
			if($curpage == $total_pages){
				$pagination_data .= '<a href="javascript:;" class="pagenumber_static pager" limit="'.$limit.'" data="'.$total_pages.'">Next<a>';
			}else{
				$nextpage = $curpage  + 1;
				$pagination_data .= '<a href="javascript:;" class="pagenumber pager" limit="'.$limit.'" data="'.$nextpage.'">Next<a>';
			}
		}

		$data['pagination'] = $pagination_data;
		
		//print_r($_SESSION['pagecheck']);

		if($categoryProducts->count() > 0 ){
			$associatedProducts = array();
			foreach ($categoryProducts as $product) {
				
				//echo "<pre>";
			 	//print_r($product->getData());
				//echo "</pre>";

				$price_value = $product->getPrice();
				//for grouped product price 	
				$p  = $product->getData();
				if($product['type_id']=="grouped"){
				    $associatedProducts[] = $product->getTypeInstance(true)->getAssociatedProducts($product);
					$price =  array();
					foreach ($associatedProducts as $singleProduct) {
					    foreach ($singleProduct as $_p) {
					       $price[] =  $_p->getPrice();
					    }
					}
					sort($price);
					$price_value = $price[0];
				}else if($product['type_id']=="configurable"){
					$price_value = $product->getFinalPrice();
				}
				$price_value = 	number_format((float)$price_value, 2, '.', '');
				//image uploaded file path
				 $mediaPath = $objectManager->get('Magento\Store\Model\StoreManagerInterface')
	                    ->getStore()
	                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
				 $img_url = $mediaPath.'catalog/product'.$product->getImage();
				 
				//image by default placeholder image path
				 if(!$product->getImage()){
					 $imageHelper = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Catalog\Helper\Image::class);
					 $img_url = $imageHelper->getDefaultPlaceholderUrl('image');      
				 }


				 $FormKey = $objectManager->get('Magento\Framework\Data\Form\FormKey'); 
				 $listBlock = $objectManager->get('\Magento\Catalog\Block\Product\ListProduct');
				 $addToCartUrl =  $listBlock->getAddToCartUrl($product);
				 $addtocart = '<form data-role="tocart-form" action="'.$addToCartUrl.'" method="post"><input type="hidden" name="product" value="'.$product->getId().'"><button type="submit" title="Add to Cart" class="action tocart"><span>Add to Cart</span></button></form>'; 	

				 $addtocart =  '<a href="'.$product->getProductUrl().'" title="Add to Cart" class="action tocart"><span>View Product</span></a>';
				
				$productData = array(
										'id' => $product->getId(),
										'sku' => $product->getSku(),
										'name' => $product->getName(),
										'price' => $price_value,
										'addtocart' => $addtocart,
										'image' => $img_url,
										'url' => $product->getProductUrl(),
									);
			    //echo $product->getName() . ' - ' . $product->getProductUrl() . '<br />';
			    $data['message']  	 = 0;
			    $data['products'][]  = $productData;

			    //$_SESSION['destroy_all'] = 'b';
			}
		}else{
			    $data['message']  	 = 1;
			    $data['products']  = array();
		}

		echo json_encode($data);
  }
}
