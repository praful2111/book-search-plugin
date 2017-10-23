<?php
/**
*Plugin Name: Library Book Search
*Description: Used to search books in Library.
*Author: Praful Patel
*Author URI: https://www.linkedin.com/in/praful2111/
*Plugin Uri: https://www.linkedin.com/in/praful2111/
*Tags: Search book, Search custome post data
*Requires at least: 4.0.0
*Tested up to: 4.8.2
*Stable tag: 1.0
*Version: 1.0.0
**/


/**** start adding scripts  ****/

function ui_book_front_scripts() {
  /*wp_enqueue_style( 'bootstrap-min-css', plugin_dir_url(__FILE__).'css/bootstrap.min.css', array(), null, 'all' );
  wp_enqueue_style( 'jquery-dataTables-min-css', plugin_dir_url(__FILE__).'css/jquery.dataTables.min.css', array(), null, 'all' );
  wp_enqueue_script( 'bootstrap-jquery', plugin_dir_url(__FILE__) .'js/bootstrap.min.js' , array('jquery'));
  wp_enqueue_script( 'datatable-jquery', plugin_dir_url(__FILE__) .'js/jquery.dataTables.min.js' , array('jquery'));*/
  wp_enqueue_script( 'jquery-validate', plugin_dir_url(__FILE__) .'js/jquery.validate.min.js' , array('jquery'));
}
//add_action('admin_init', 'ui_book_front_scripts' );
add_action('wp_enqueue_scripts', 'ui_book_front_scripts' );

/*
Register Custom Post For Books
*/
function my_custom_post_books() {
  $labels = array(
    'name'               => _x( 'Books', 'post type general name' ),
    'singular_name'      => _x( 'Books', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'Books' ),
    'add_new_item'       => __( 'Add New Books' ),
    'edit_item'          => __( 'Edit Books' ),
    'new_item'           => __( 'New Books' ),
    'all_items'          => __( 'All Books' ),
    'view_item'          => __( 'View Books' ),
    'search_items'       => __( 'Search Books' ),
    'not_found'          => __( 'No found' ),
    'not_found_in_trash' => __( 'No found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'Books'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => '',
    'query_var' => true,
    'public'        => true,
    'menu_position' => 8,
    'supports'      => array( 'title', 'editor', 'thumbnail',  'page-attributes'),
    'has_archive'   => true,
  );
  register_post_type( 'books', $args );
}

add_action( 'init', 'my_custom_post_books' );

//of author and publisher.
function books_taxonomy() {
  // Add new "Locations" taxonomy to Posts
  register_taxonomy('books_author_category', 'books', array(
    // Hierarchical taxonomy (like categories)
    'hierarchical' => true,
    'show_admin_column' => true,
    // This array of options controls the labels displayed in the WordPress Admin UI  
     'labels' => array(
      'name' => _x( 'Author', 'taxonomy general name' ),
      'singular_name' => _x( 'Author', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Author' ),
      'all_items' => __( 'All Author' ),
      'parent_item' => __( 'Parent Author' ),
      'parent_item_colon' => __( 'Parent Author:' ),
      'edit_item' => __( 'Edit Author' ),
      'update_item' => __( 'Update Author' ),
      'add_new_item' => __( 'Add New Author' ),
      'new_item_name' => __( 'New Author Name' ),
      'menu_name' => __( 'Author' ),
    ),
    // Control the slugs used for this taxonomy
    'rewrite' => array(
      'slug' => 'books_author_category',
      'with_front' => false,
      'hierarchical' => true
    ),
  ));
  register_taxonomy('books_publisher_category', 'books', array(
    // Hierarchical taxonomy (like categories)
    'hierarchical' => true,
    'show_admin_column' => true,
    // This array of options controls the labels displayed in the WordPress Admin UI  
     'labels' => array(
      'name' => _x( 'Publisher', 'taxonomy general name' ),
      'singular_name' => _x( 'Publisher', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Publisher' ),
      'all_items' => __( 'All Publisher' ),
      'parent_item' => __( 'Parent Publisher' ),
      'parent_item_colon' => __( 'Parent Publisher:' ),
      'edit_item' => __( 'Edit Publisher' ),
      'update_item' => __( 'Update Publisher' ),
      'add_new_item' => __( 'Add New Publisher' ),
      'new_item_name' => __( 'New Publisher Name' ),
      'menu_name' => __( 'Publisher' ),
    ),
    // Control the slugs used for this taxonomy
    'rewrite' => array(
      'slug' => 'books_publisher_category',
      'with_front' => false,
      'hierarchical' => true
    ),
  ));
}
add_action( 'init', 'books_taxonomy', 0 ); 

add_action('admin_menu', 'wpdocs_register_my_custom_submenu_page');
 
function wpdocs_register_my_custom_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=books',
        'Help',
        'Help',
        'manage_options',
        'my-custom-submenu-page',
        'wpdocs_my_custom_submenu_page_callback' );
}
 
function wpdocs_my_custom_submenu_page_callback() {
    echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
        echo '<h2>Help for Books Search Plugin</h2>';
        echo '<h5>You can use <b>[form_call]</b> Shortcode.</h5>';
    echo '</div>';
}

function wp_call_meta_box($post_type, $post)
{
   add_meta_box(
       'books_box',
       __('Book Details', 'wp_praful_plugin'),
       'wp_display_meta_box',
       'books',
       'advanced',
       'default'
   );
}
add_action('add_meta_boxes', 'wp_call_meta_box', 10, 2);

function wp_display_meta_box($post, $args)
{
   wp_nonce_field(plugins_url(__FILE__), 'wp_praful_plugin_noncename');
?>
   <p>
       <label for="book-price"><?php _e('Price', 'wp_praful_plugin'); ?>: </label>
       <input type="text" name="book-price" value="<?php echo get_post_meta($post->ID, 'book-price', true); ?>" />
   </p>

   <p>
       <label for="book-rating"><?php _e('Rating', 'wp_praful_plugin'); ?>: </label>
       <input type="number" name="book-rating" value="<?php echo get_post_meta($post->ID, 'book-rating', true); ?>" />
       <em>Rating must be 1 to 5.</em>
   </p>
<?php
}

add_action('save_post', 'wp_save_meta_box', 10, 2);
function wp_save_meta_box($post_id, $post)
{
   if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
       return;

   if('books' == $_POST['post_type'])
   {
       if(!current_user_can('edit_page', $post_id))
           return;
   }
   else
       if(!current_user_can('edit_post', $post_id))
           return;

   if(isset($_POST['wp_praful_plugin_noncename']) && wp_verify_nonce($_POST['wp_praful_plugin_noncename'], plugins_url(__FILE__)) && check_admin_referer(plugins_url(__FILE__), 'wp_praful_plugin_noncename'))
   {
       if(!empty($_POST['book-price']) && is_numeric($_POST['book-rating']))
       {
           update_post_meta($post_id, 'book-price', $_POST['book-price']);
           update_post_meta($post_id, 'book-rating', $_POST['book-rating']);
       }
   }
   return;
}

/*-------------------- Start code for Add Shortcode-----------------------------*/
add_shortcode('form_call','ui_add_emp_info_function');

function ui_add_emp_info_function(){
  global $wpdb;
 
  echo "<div class='wrap'>";
  echo "<h1>Book Search</h1>";
  echo "<div id='mssd'></div>";
  echo "<hr>";
  echo "<div style='color:red;border:0px 1px red;'>".$mssg."</div>";
  echo liveSearchForm();
  echo "</div>";
  ?>
  <div class="box-listing">
  	<div class="box-listing-inner">
  		<div class="overlay"></div>
  		<div id="list_book"></div>
  	</div>
  </div>
  <script>
      jQuery(document).ready(function(){
          jQuery('#createpost').validate({
            rules:{
              bname: 'required',
/*              bauthor: {
                required: true,
              },
              bpublisher: 'required',
              estreet: 'required',
              brating: 'required'
*/            },
            messages:{
              bname: 'Please enter Book Name.',
/*              bauthor: {
                required: 'Please enter Book Author.',
              },
              bpublisher: 'Please enter Book Publisher.',
              brating: 'Please Select Book Rating.'
*/            },
            submitHandler: function(form){
            	var formdatas = jQuery('#createpost').serialize();
              	var admin_ajax = "<?php echo admin_url( 'admin-ajax.php' );?>";
              	formdatas = 'action=search_library_book&'+formdatas;

				// We can also pass the url value separately from ajaxurl for front end AJAX implementations
				jQuery.post(admin_ajax, formdatas, function(response) {
					jQuery("#list_book").html(response);
					return false;
				});
	              return false;
            }
          });
      });
  </script>
  <?php
}

function liveSearchForm(){
?>
<style>
.error{color:red;}
input.form-control.error {border-color: #F00;}
#slidecontainer {width: 100%;}
.slider {-webkit-appearance: none;width: 100%;height: 25px;background: #d3d3d3;outline: none;opacity: 0.7;-webkit-transition: .2s;transition: opacity .2s;}
.slider:hover {opacity: 1;}
.slider::-webkit-slider-thumb {-webkit-appearance: none;appearance: none;width: 25px;height: 25px;background: #000;cursor: pointer;}
.slider::-moz-range-thumb {width: 25px;height: 25px;background: #000;cursor: pointer;}
.col-md-6 {width: 48%;float: left;margin-right: 2%;}
.col-md-12 {float: left;width: 100%;}
input#postform {float: left;margin-top: 20px;}
select#brating,select#bauthor,select#bpublisher {width: 100%;}
.box-listing {width: 100%;float: left;border: 1px solid #cdcdcd;margin-top: 30px;padding: 20px;}
.box-listing .box-listing-inner{width: 100%;float: left;}
#list_book {width: 100%;float: left;}
#list_book ul {padding: 0;margin: 0;padding-left: 30px;}
#list_book ul li {width: 100%;float: left;font-size: 14px;font-weight: 600;margin-bottom: 8px;}
</style>
<script type="text/javascript">
	
function updateTextInput(val) {
	document.getElementById("priveview").value = val;
          jQuery('#priveview').html(val);
        }

</script>
<div class="container">
	<div class="row">
		<form name="createpost" id="createpost" method="POST" action="">
			<div class="col-md-12">
				<div class="form-group">
					<label for="bname">Book Name:</label>
					<input type="text" id="bname" name="bname" class="form-control">
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="bauthor">Book Author:</label>
					<select name="bauthor" id="bauthor" class="form-control">
						<option value="">Select Author</option>
						<?php
							$books_author = get_terms( array('taxonomy' => 'books_author_category') );
							if(count($books_author)>0){
								foreach ($books_author as $key => $value) {
									echo '<option value="'.$value->slug.'">'.$value->name.'</option>';		
								}
							}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="bpublisher">Book Publisher:</label>
					<select name="bpublisher" id="bpublisher" class="form-control">
						<option value="">Select Author</option>
						<?php
							$books_publisher = get_terms( array('taxonomy' => 'books_publisher_category') );
							if(count($books_publisher)>0){
								foreach ($books_publisher as $key => $value) {
									echo '<option value="'.$value->slug.'">'.$value->name.'</option>';		
								}
							}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="brating">Book Rating:</label>
					<select name="brating" id="brating" class="form-control">
					<option value="">Select Rating</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					</select>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="bprice">Price:</label>
					<input type="range" min="1" max="3000" value="50" onchange="updateTextInput(this.value);" class="slider form-control" id="bprice" name="bprice" >
					<div id="priveview"></div>
				</div>
			</div>
			<input type="hidden" name="ui-emp-ajax-nonce" id="ui-emp-ajax-nonce" value="<?php echo wp_create_nonce( 'ui-emp-ajax-nonce' )?>" />
			<input type="submit" id="postform" name="submit" class="btn btn-primary" value="Post">
		</form>
	</div>
</div>
<?php
}

add_action( 'wp_ajax_search_library_book', 'search_library_book' );
add_action( 'wp_ajax_nopriv_search_library_book', 'search_library_book' );
function search_library_book() {
	$bname = $_REQUEST['bname'];
    $bauthor = $_REQUEST['bauthor'];
    $bpublisher = $_REQUEST['bpublisher'];
    $brating = $_REQUEST['brating'];
    $bprice = $_REQUEST['bprice'];

    $tax_query = array('relation' => 'OR');

    if (!empty($bauthor))
    {
        $tax_query[] =  array(
            'taxonomy' => 'books_author_category',
            'field' => 'slug',
            'terms' => array( $bauthor)
        );
    }
    if (!empty($bpublisher))
    {
        $tax_query[] =  array(
            'taxonomy' => 'books_publisher_category',
            'field' => 'slug',
            'terms' => array( $bpublisher )
        );
    }

    $the_query = new WP_Query(array('s' => $bname, 'post_type' => 'books', 'tax_query' => $tax_query, 'post_status' => 'publish', 'orderby' => 'id', 'order' => 'DESC'));    
    
    $args = array(
	    'post_type' => 'books',
	    'post_status' => 'publish',
	    'orderby' => 'id',
	    'order' => 'DESC',
	    'tax_query' => $tax_query
	);
	$query = new WP_Query( $args );
	$books = "";

	if ( $the_query->have_posts() ) {
		$books .= '<table>';
		$i=1;
		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			$price = get_post_meta($the_query->post->ID, 'book-price', true);
			$rating = get_post_meta($the_query->post->ID, 'book-rating', true);
			$books .='<tr>';
			$books .='<td>No</td>';
			$books .='<td>Book Name</td>';
			$books .='<td>Price</td>';
			$books .='<td>Author</td>';
			$books .='<td>Publisher</td>';
			$books .='<td>Rating</td>';
			$books .='</tr>';

			if($price >= $bprice &&  $price <= '3000'){
				if($rating == $brating){
					$books .='<tr class="'.$the_query->post->ID.'">';
					$books .='<td>'.$i.'</td>';
					$books .= '<td><a href="'.get_permalink().'">' . get_the_title() . '</a></td>';
					$books .= '<td>'.$price.'</td>';
					$books .= '<td>';
$categories_aut = get_the_terms($post->ID, "books_author_category");
                                foreach ( $categories_aut as $cat){
                                 $books .= $cat->name;
                                }
                    $books .= '</td>';
                    $books .= '<td>';
$categories_pub = get_the_terms($post->ID, "books_publisher_category");
                                foreach ( $categories_pub as $cat1){
                                  $books .= $cat1->name;
                                }
                    $books .= '</td>';
                    $books .= '<td>';
                    if($rating>0){
	                    for($j=1;$j<=$rating;$j++)
	                    {
	                    		$books .= "<img src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQDEfWProHr4XdqrzDQZLEvqLnC_PPngE85THocEN46JkF0OPt4BQ' width='10' height='10'>";
	                    }
                	}
                	$ratingnul = 5 - $rating;
                    for($p=1;$p<=$ratingnul;$p++)
                    {
                    		$books .= "<img src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTlWQbQ7SewVqCXSqIYTiJf2AwR7lsKuEVa9FUEnlay80IHn9ZC' width='10' height='10'>";
                    }
                    $books .= '</td>';



					$books .='</tr>';

				}else{
					$books .= '<tr><td>No record found!</td></tr>';
				}
			}else{
				$books .= '<tr><td>No record found!</td></tr>';
			}
			$i++;
		}
		$books .= '</table>';
		/* Restore original Post Data */
		wp_reset_postdata();
		$code = 1;
	} else {
		$books .= "<table><tr><td>No record found!</td></tr></table>";
		$code = 0;
	}
    
    /*$results = array('books' => $books,'code' =>$code);
    $response = json_encode($results);
    print_r($response);*/
    echo $books;
    die();
}
