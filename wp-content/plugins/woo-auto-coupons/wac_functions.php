<?php
/*
Plugin Name: Auto Coupons for WooCommerce
Description: Apply WooCommerce Coupons automatically.
Author: Richard Lerma Design & Development
Version: 2.1.5
Text Domain: r1cm
Author URI: https://richardlerma.com/plugins/
Copyright: (c) 2019-2020 - richardlerma.com - All Rights Reserved
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
WC requires at least: 3.5.0
WC tested up to: 5.9
*/

global $wac_version; $wac_version='2.1.5';
if(!defined('ABSPATH')) exit;

function wac_error() {file_put_contents(dirname(__file__).'/install_log.txt', ob_get_contents());}
if(defined('WP_DEBUG') && true===WP_DEBUG) add_action('activated_plugin','wac_error');

function wac_adminMenu() {
  if(current_user_can('manage_options')) {
    add_options_page('Auto Coupons','Auto Coupons','manage_options','woo-auto-coupons','wac_admin');
  }
}
//add_action('admin_menu','wac_adminMenu');

function wac_admin() { // Future admin settings
  global $wpdb;
  global $wac_version;
  wp_enqueue_style('wac_style',plugins_url('assets/wac_min.css?v='.$wac_version,__FILE__));
}
//add_shortcode('wp_admin','wac_admin');

function wac_activate($upgrade) {
  global $wpdb;
  global $wac_version;
  require_once(ABSPATH.basename(get_admin_url()).'/includes/upgrade.php');
  update_option('wac_db_version',$wac_version,'no');
  if(function_exists('wac_pro_ping'))wac_pro_ping(2);
}
register_activation_hook(__FILE__,'wac_activate');
function wac_shh() { ?><style type='text/css'>div.error{display:none!important}</style><?php }
if(wac_is_path(basename(get_admin_url()).'/plugins.php') && wac_is_path('plugin=woo-auto-coupons')) add_action('admin_head','wac_shh'); 

function wac_add_action_links($links) {
  $settings_url=get_admin_url(null,'edit.php?post_type=shop_coupon');
  $support_url='https://richardlerma.com/plugins/';
  $links[]='<a href="'.$support_url.'">Support</a>';
  array_push($links,'<a href="'.$settings_url.'">Settings</a>');
  return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__),'wac_add_action_links');

function wac_uninstall() {delete_option('wac_db_version');}
register_uninstall_hook(__FILE__,'wac_uninstall');

// Run Query
function wac_r($q,$t=NULL) {
  include_once(ABSPATH .'wp-includes/pluggable.php'); // If called prior to pluggable loaded natively
  require_once(ABSPATH .'wp-includes/wp-db.php');
  global $wpdb;
  if(!$wpdb) $wpdb=new wpdb(DB_USER,DB_PASSWORD,DB_NAME,DB_HOST);
  $prf=$wpdb->prefix;
  $s=str_replace(' wp_',' '.$prf,$q);
  $s=str_replace($prf.str_replace('wp_','',$prf),$prf,$s);
  if(strpos($s,'DELETE')!==false || strpos($s,'INSERT')!==false) $r=$wpdb->query($s); else $r=$wpdb->get_results($s,OBJECT);
  if($t) {echo $wpdb->last_error."<br>";echo $s;}
  if($r) return $r;
}

// Strict Comparison
function wac_in ($n,$h) {
  if(!is_array($h)) $h=explode(',',$h);
  if(in_array($n,$h,true)!==false) return true;return false;
}

// Loose Comparison (regardless of vars types)
function wac_in_like($n,$h) {
  if(!is_array($h)) $h=explode(',',$h);
  foreach($h as $item) {
    if(!empty($item)) if(stripos($n,$item)!==false) return true;
  }
  return false;
}

// Path Comparison
function wac_is_path($pages) {
  $page=strtolower($_SERVER['REQUEST_URI']);
  return wac_in_like($page,$pages);
}

// Apply WC Coupons
function wac_is_coupon_valid($coupon_code,$dsp_err=0) {
  $coupon=new \WC_Coupon($coupon_code);   
  $discounts=new \WC_Discounts(WC()->cart);
  $status=$discounts->is_coupon_valid($coupon);
  if(is_wp_error($status)) {if($dsp_err>0) return $status->get_error_message(); else return false;} else return true;
}

function wac_qty_in_cart($product_id) {
  global $woocommerce;
  $qty=0;
  $product_id=",$product_id,";
	foreach($woocommerce->cart->get_cart() as $cart_item_keys=>$cart_item) {
    if(wac_in_like(",{$cart_item['product_id']},",$product_id) || wac_in_like(",{$cart_item['variation_id']},",$product_id)) $qty=$qty+$cart_item['quantity'];
  }
  return $qty;
}

function wac_apply_coupons() {
  if(current_user_can('administrator') && isset($_GET['troubleshoot'])) $trb=1; else $trb=0;
  $req=$req2='';
  $meta='_wc_%_apply';
  $cart_ip=sanitize_text_field($_SERVER['REMOTE_ADDR']);
  global $woocommerce,$coupon_codes;
  $cart=$woocommerce->cart; //print_r($cart);
  $cart_items=$auto_apply_indv=0;
  $cart_qty=$woocommerce->cart->get_cart_contents_count();

  if(isset($_GET['coupon'])) {$coupon=sanitize_text_field($_GET['coupon']); $req.=" AND post_title='$coupon'"; $meta='_wc_url_apply';}
  else {
    if($cart_qty>0) {
      $cache_coupon=get_transient("wac_$cart_ip");
      if(!empty($cache_coupon)) {$coupon=$cache_coupon; delete_transient("wac_$cart_ip");}
    }
  }
  
  if($trb<1) {
    $req.=" AND IFNULL(NULLIF(FROM_UNIXTIME(x.meta_value),''),NOW())>=NOW()";
    $req2.=" WHERE(individual='yes' OR apply IS NOT NULL)";
  }
    
  $coupons=wac_r("
    SELECT *
    -- ,(SELECT GROUP_CONCAT(DISTINCT post_title) FROM wp_posts WHERE FIND_IN_SET(ID,product_ids)>0)product
      FROM (
      SELECT p.ID post_id,post_title coupon_code 
      ,(SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='product_ids' LIMIT 1)product_ids
      ,(SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='product_categories')category
      ,(SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='individual_use' LIMIT 1)individual
      ,(SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='coupon_amount' LIMIT 1)coupon_amount
      ,(SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='_wc_min_qty' LIMIT 1)min_qty
      ,(SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='_wc_max_qty' LIMIT 1)max_qty
      ,(SELECT meta_value FROM wp_postmeta WHERE post_id=p.ID AND meta_key='_wc_qty_ntf' LIMIT 1)qty_ntf
      ,(SELECT meta_key FROM wp_postmeta WHERE post_id=p.ID AND meta_key LIKE '$meta' AND meta_value='yes' LIMIT 1)apply
      ,DATE_FORMAT(FROM_UNIXTIME(meta_value),'%m-%d-%Y')exp_date
      ,CASE WHEN FROM_UNIXTIME(meta_value)<NOW() THEN 1 ELSE 0 END exp
      FROM wp_posts p
      LEFT JOIN wp_postmeta x ON x.post_id=p.ID AND x.meta_key='date_expires'
      WHERE post_type='shop_coupon'
      AND post_status='publish'
      $req
    )a
    $req2
    ORDER BY exp,individual DESC,CAST(coupon_amount AS SIGNED) DESC;");
  if(!$coupons) return; 

  $user_removed_coupon=wac_get_removed_coupon();
  foreach($cart->cart_contents as $cart_item_key=>$cart_item) $cart_items++;

  $coupon_codes[]='';
  $coupon_count=count($coupons);
  foreach($coupons as $c) {
    $valid=1;
    $coupon_code=ucwords($c->coupon_code);
    $reason=$individual_use=$apply_type='';
    if($c->product_ids>0) $qty_in_cart=wac_qty_in_cart($c->product_ids); else $qty_in_cart=$cart_qty;

    $wc_qty_ntf=unserialize($c->qty_ntf);
    if(empty($wc_qty_ntf)) $wc_qty_ntf=array(1,-1);
    $wc_min_ntf=$wc_qty_ntf[0];
    $wc_max_ntf=$wc_qty_ntf[1];
    
    if($c->individual=='yes') $individual_use='[Individual Use]';
    if($user_removed_coupon==strtolower($coupon_code)) {$valid=0; if($trb>0) $reason.=" Manually removed from the cart."; else continue;}
    if(!empty($auto_apply_indv)) {$valid=0; if($trb>0) $reason.=" Individual use coupon [$auto_apply_indv] has already been applied."; else continue;}
    if($c->exp>0) {$valid=0; if($trb>0) $reason.=" Expired {$c->exp_date}."; else continue;}
    if(!empty($c->product_ids) && $qty_in_cart<1 && empty($coupon)) {$valid=0; if($trb>0) $reason.=' No qualifying cart items.'; else continue;}
    if(wac_in(strtolower($coupon_code),$cart->applied_coupons)) $applied=1; else $applied=0;
    if($cart_items==0 && empty($coupon)) {$valid=0; if($trb>0) $reason.=' No Items in Cart.'; else continue;}

    if($valid>0 && ($c->min_qty>0 || $c->max_qty>0)) { // Check Qty
      $item='';

      if(!empty($c->product_ids)) { // Prd Restrict
        $prd_ids=explode(",",$c->product_ids);
        $prd_ct=count($prd_ids);
        $i=1;
        foreach($prd_ids as $prd) {
          if(!empty($item)) if($i==$prd_ct) $item.=' or '; else  $item.=', ';
          $item.=get_the_title($prd);
          $i++;
        }
      }
      
      if(empty($item) && !empty($c->category)) { // Cat Restrict
        $p_cat=unserialize($c->category);
        $cat_ct=count($p_cat);
        $i=1;
        foreach($p_cat as $cat) {
          if(!empty($item)) if($i==$cat_ct) $item.=' or '; else  $item.=', ';
          $item.=get_term($cat)->name;
          $i++;
        }
      }

      if(empty($item)) $item='item';
      if($c->min_qty>0 && $qty_in_cart<$c->min_qty) {$valid=0; if($trb>0) $reason.=" Min $item quantity: {$c->min_qty}. Quantity in cart: $qty_in_cart."; elseif($wc_min_ntf>0 && wac_is_path('/cart')!==false) echo "<div class='woocommerce-message wac'><i class='fas fa-sort-amount-up'></i> Add ".floatval($c->min_qty-$qty_in_cart)." more $item to qualify for a discount.</div>";}
      if($c->max_qty>0 && $qty_in_cart>$c->max_qty) {$valid=0; if($trb>0) $reason.=" Max $item quantity: {$c->max_qty}. Quantity in cart: $qty_in_cart."; elseif($wc_max_ntf>0 && wac_is_path('/cart')!==false) echo "<div class='woocommerce-message wac'><i class='fas fa-sort-amount-down'></i> Reduce $item quantity to ".$c->max_qty." to qualify for a discount.</div>";}
    }

    if($valid>0) $valid=wac_is_coupon_valid($coupon_code);

    if($valid>0 && ($applied>0 || !empty($coupon) || $c->apply=='_wc_auto_apply')) {
      if(!$applied) $woocommerce->cart->add_discount($coupon_code); // Apply To Cart
      if($cart_qty==0) set_transient("wac_$cart_ip",$coupon_code,3600);
      $apply_type='';
      if($c->apply=='_wc_auto_apply') {
        $apply_type=' Auto-'; 
        if(!empty($individual_use)) $auto_apply_indv=$coupon_code; 
        array_push($coupon_codes,$coupon_code); // Style line item
      }
      if($trb>0) echo "<div class='woocommerce-message wac trb'><i class='fas fa-check-circle'></i> $coupon_code - {$apply_type}Applied successfully $individual_use</div>";
    } elseif($valid>0 && $trb>0) {
      $apply_type=' via manual entry';
      if(stripos($c->apply,'url')!==false) $apply_type.=' or URL';
      echo "<div class='woocommerce-message wac trb'><i class='fas fa-check-circle'></i> $coupon_code - Eligible $apply_type. $individual_use</div>";
    }

    if($valid==0) {
      if($applied && stripos($reason,'Manual')===false) $woocommerce->cart->remove_coupon($coupon_code); // Remove From Cart
      if(!$applied && $trb>0) {
        if(empty($reason)) $reason=wac_is_coupon_valid($coupon_code,1);
        echo "<div class='woocommerce-message wac trb'><i class='fas fa-exclamation-triangle'></i> $coupon_code -$reason</div>";
      }
    }

  }
  if(wac_is_path('ajax')===false) add_action('wp_footer','wac_style_coupons');
}

add_action('woocommerce_add_to_cart','wac_apply_coupons'); // Product
add_action('woocommerce_before_checkout_form','wac_apply_coupons'); // Cart
add_action('woocommerce_before_cart','wac_apply_coupons'); // Checkout

if(isset($_GET['coupon'])) add_action('template_redirect','wac_apply_coupons'); // URL Apply

function wac_cart_coupon() {if(!isset($_POST['coupon_code'])) return; $coupon=$_POST['coupon_code'];$user_removed_coupon=wac_get_removed_coupon(); if($user_removed_coupon==strtolower($coupon)) wac_cache_coupon();}
if(isset($_POST['coupon_code'])) wac_cart_coupon();  // WC Apply

// WC Session Removed Coupon
function wac_sess() {
  if(!isset($_SERVER['HTTP_COOKIE'])) return;
  return sanitize_text_field(explode(';',$_SERVER['HTTP_COOKIE'])[0]);
}

function wac_cache_coupon($coupon='') {
  $sess=wac_sess();
  if(!empty($sess)) if(empty($coupon)) delete_transient("wac_rc_$sess"); else set_transient("wac_rc_$sess",strtolower($coupon),86400); 
}
add_action('woocommerce_removed_coupon','wac_cache_coupon',10,1); // WC Remove

function wac_get_removed_coupon() {
  $sess=wac_sess();
  if(!empty($sess)) return get_transient("wac_rc_$sess");
}

function wac_style_coupons() {
  global $coupon_codes;
  if(empty($coupon_codes)) return;
  foreach($coupon_codes as $coupon_code) {
    $p=wac_r("
      SELECT post_title coupon_code, p.meta_value prefix
      ,(SELECT 1 FROM wp_postmeta WHERE post_id=c.ID AND meta_key='_wc_auto_apply' AND meta_value='yes' LIMIT 1)auto_apply
      FROM wp_posts c
      JOIN wp_postmeta p ON p.post_id=c.ID AND p.meta_key='_wc_prefix'
      WHERE post_type='shop_coupon'
      AND post_status='publish'
      AND post_title='$coupon_code'
      LIMIT 1;");

    $coupon_code=strtolower(str_replace(' ','-',$coupon_code));
    if($p) {
      $prefix=$p[0]->prefix;
      if($prefix!=='Coupon:') { ?><script type='text/javascript'>setTimeout(function(){wac_style_cpn(<?php echo "'$prefix','$coupon_code'";?>);},500);</script><?php }
      if($p[0]->auto_apply>0) {?><style>.cart-discount.coupon-<?php echo $coupon_code;?> .woocommerce-remove-coupon{display:none!important}</style><?php }
    }
  } ?>
  <style>
    .woocommerce .wac .fas{zoom:1.3}
    .woocommerce .wac .fa-sort-amount-up{color:#77c777}
    .woocommerce .wac .fa-sort-amount-down{color:darkorange}
    .woocommerce .wac.trb:before{content:''}
    .woocommerce .wac.trb{background:rgba(220,220,220,.5);color:#777}
    .woocommerce .wac.trb .fa-exclamation-triangle{color:#ffa2a2}
    .woocommerce .wac.trb .fa-check-circle{color:#77c777}
    .woocommerce-message.wac.trb{margin-bottom:1em;padding:1em}
    .woocommerce .cart-discount td[data-title],.woocommerce .cart-discount .amount{color:green}
    .woocommerce .cart-discount td:after,.woocommerce .cart-discount td[data-title]:after{content:"\00a0 \f14a";display:inline-block;font-family:'Font Awesome 5 Free';position:absolute;margin-top:-.5em;opacity:0;-webkit-transition:all 1s;transition:all 1s}
    .woocommerce .cart-discount td[data-title]:before{color:#6d6d6d;font-weight:normal;white-space:pre-wrap}
    .woocommerce .cart-discount.anm td:after{opacity:1;margin-left:-3em;color:green;font-size:2em}
  </style>
  <script type='text/javascript'>
    function wac_style_cpn(prefix,coupon_code) {
      var coupon_class='coupon-'+coupon_code;
      if(!document.getElementsByClassName(coupon_class)) return;
      cpn=document.getElementsByClassName(coupon_class);
      for(i=0; i<cpn.length; i++) cpn[i].innerHTML=cpn[i].innerHTML.replace(/Coupon:/g,prefix);
    }
    setTimeout(function(){wac_style_cpn('<?php echo $coupon_code;?>');},1000);
  </script><?php 
}


// WC Admin Coupon Quantity
function wac_qty_add_coupon_field() {
  $post_id=intval($_GET['post']);
  $wc_qty_ntf=get_post_meta($post_id,'_wc_qty_ntf');
  if(empty($wc_qty_ntf)) $wc_qty_ntf=array(array(1,-1));
  $wc_min_ntf=$wc_qty_ntf[0][0];
  $wc_max_ntf=$wc_qty_ntf[0][1];
  $wc_min_ntf_check=$wc_max_ntf_check='';
  if($wc_min_ntf>0) $wc_min_ntf_check='checked';
  if($wc_max_ntf>0) $wc_max_ntf_check='checked';
  echo "<div class='options_group'>
    <p class='form-field' style='background:#0073aa;margin:1em 0 0'><label style='color:#fff;font-variant-caps:all-small-caps;font-size:1.3em'><span class='dashicons dashicons-admin-settings' style='vertical-align:text-top'></span> Auto Coupons</label></p>";
    woocommerce_wp_checkbox(array('id'=>'_wc_url_apply','label'=> __('Apply via URL','wc-url-apply'),'placeholder'=>'','description'=> __('This setting will apply coupon to qualifying carts upon visiting /?coupon=<span class=\'cname\'>COUPON_NAME</span>.<br><span class=\'wac_restr\'>&#9888; <i>If usage restrictions are present, product must be in cart when URL is visited.</i></span>','wc-url-apply')));
    woocommerce_wp_checkbox(array('id'=>'_wc_auto_apply','label'=> __('Auto Apply','wc-auto-apply'),'placeholder'=>'','desc_tip'=>'true','description'=> __('This setting will apply the coupon to ALL qualifying carts on the cart and checkout page.','wc-auto-apply')));
    woocommerce_wp_text_input(array('id'=>'_wc_prefix','label'=> __('Line Item Name','wc-prefix'),'placeholder'=>'Coupon:','desc_tip'=>'true','description'=> __('Enter a line item name to be shown before the coupon name in cart. e.g. Coupon: ','wc-prefix')));
  echo "<div class='_wc_qty'>";
    woocommerce_wp_text_input(array('id'=>'_wc_min_qty','label'=> __('Min Cart Quantity','wc-min-qty'),'placeholder'=>'Optional','desc_tip'=>'true','description'=> __('Set a minimum product quantity for this coupon. Enter a number, 1 or greater.','wc-min-qty')));
  echo "<p class='form-field ntf_group_field'><label>Min Notification</label><input type='checkbox' $wc_min_ntf_check onclick=\"wac_ntf('min',this.checked);\"> Display notification when quantity in cart is less than min quantity of coupon.</p><div class='max_group'>";
    woocommerce_wp_text_input(array('id'=>'_wc_max_qty','label'=> __('Max Cart Quantity','wc-max-qty'),'placeholder'=>'Optional','desc_tip'=>'true','description'=> __('Set a maximum quantity limit allowed per coupon. Enter a number, 1 or greater.','wc-max-qty')));
  echo "<p class='form-field ntf_group_field'><label>Max Notification</label><input type='checkbox' $wc_max_ntf_check onclick=\"wac_ntf('max',this.checked);\"> Display notification when quantity in cart is more than max quantity of coupon.</p>

          <input type='hidden' name='_wc_qty_ntf[]' id='wc_min_ntf' value='$wc_min_ntf'>
          <input type='hidden' name='_wc_qty_ntf[]' id='wc_max_ntf' value='$wc_max_ntf'>
        </div>
      </div>
    </div>
    <datalist id='wc_prefix'>
      <option>Coupon:</option>
      <option>Discount:</option>
      <option>Promo:</option>
      <option>Promotion:</option>
      <option>Sale:</option>
    </datalist>
    <style>
      .woocommerce_options_panel p._wc_url_apply_field,.woocommerce_options_panel p._wc_prefix_field,.woocommerce_options_panel p._wc_max_qty_field,div.max_group{background:#f8f8f8;padding-top:1em!important;padding-bottom:1em!important;margin-top:0}
      .woocommerce_options_panel p._wc_min_qty_field,.woocommerce_options_panel p._wc_max_qty_field{padding-bottom:0!important}
      .woocommerce_options_panel p.ntf_group_field{padding-top:0!important}
      #_wc_prefix{float:left;width:50%}
      .wac_restr{color:#0073ab;font-size:.9em}
    </style>
    <script type='text/javascript'>
      function wac_ntf(field,checked) {
        var i=document.getElementById('wc_'+field+'_ntf');
        if(checked>0) i.value=1; else i.value=-1;
      }
      
      function wac_cname() {
        var ctitle=document.getElementById('title').value;
        var cname=document.getElementsByClassName('cname');
        if(ctitle.length>0) {for(var i=0; i<cname.length; i++) cname[i].innerHTML=ctitle;}
      }
      wac_cname();
      
      function wac_dsp_options() {
        wcua=document.getElementById('_wc_url_apply');
        wcaa=document.getElementById('_wc_auto_apply');
        wcmn=document.getElementById('_wc_min_qty');
        wcmx=document.getElementById('_wc_max_qty');
        if(wcmn.value>0) tmn=wcmn.value;
        if(wcmx.value>0) tmx=wcmx.value;
        if(wcaa.checked) {
          document.getElementsByClassName('_wc_qty')[0].style.visibility='visible';
          if(typeof tmn!=='undefined') wcmn.value=tmn;
          if(typeof tmx!=='undefined') wcmx.value=tmx;
          if(wcmn.value>0 && wcmx.value && parseInt(wcmn.value)>parseInt(wcmx.value)) alert('Min quantity must be less than max quantity.');
        } else {
          wcmn.value=wcmx.value='';
          document.getElementsByClassName('_wc_qty')[0].style.visibility='hidden';
        }
        if(wcua.checked || wcaa.checked) {
          document.getElementsByClassName('_wc_prefix_field')[0].style.visibility='visible';
          document.getElementById('_wc_prefix').setAttribute('list','wc_prefix');
          document.getElementById('_wc_prefix').setAttribute('type','search');
        }
        else document.getElementsByClassName('_wc_prefix_field')[0].style.visibility='hidden';
      }
      wac_dsp_options();
      
      function wac_val_qty() {
        wcmn=document.getElementById('_wc_min_qty');
        wcmx=document.getElementById('_wc_max_qty');
        if(wcmn.value>0 && wcmx.value && parseInt(wcmn.value)>parseInt(wcmx.value)) {
          alert('Min quantity must be less than max quantity.');
          wcmn.style.borderColor='red';
        } else wcmn.style.borderColor='';
      }

      function wac_activate(wac_id) {
        cpn=document.getElementById('title').value;
        expiry=document.getElementById('expiry_date');
        var todayDate=new Date().toISOString().slice(0,10);
        if(expiry.value.length>0 && expiry.value<=todayDate){alert('This coupon expired on '+expiry.value+' and it cannot be auto-applied.');expiry.focus();return false;}
        if(wac_id=='_wc_auto_apply')alert('This setting will apply the '+cpn+' coupon to ALL qualifying carts on the cart and checkout page.');
        if(wac_id=='_wc_url_apply')alert('This setting will apply the coupon to qualifying carts upon visiting:\\n/?coupon='+cpn+'.');
        return true;
      }
      
      document.getElementById('title').onchange=function(){wac_cname();};
      document.getElementById('titlewrap').onclick=function(){wac_cname();};
      document.getElementById('_wc_min_qty').setAttribute('pattern','[0-9 ]{0,9}');
      document.getElementById('_wc_max_qty').setAttribute('pattern','[0-9 ]{0,9}');
      document.getElementById('_wc_min_qty').setAttribute('title','Min quantity (number only)');
      document.getElementById('_wc_max_qty').setAttribute('title','Max quantity (number only)');
      document.getElementById('_wc_min_qty').onchange=function(){wac_val_qty();};
      document.getElementById('_wc_max_qty').onchange=function(){wac_val_qty();};
      document.getElementById('_wc_auto_apply').onclick=function(){if(this.checked)if(!wac_activate(this.id))this.checked=false;wac_dsp_options();}
      document.getElementById('_wc_url_apply').onclick=function() {if(this.checked)if(!wac_activate(this.id))this.checked=false;wac_dsp_options();}
    </script>
  ";
}
add_action('woocommerce_coupon_options','wac_qty_add_coupon_field');


// WC Save Admin Coupon Fields
function wac_qty_save_coupon($post_id,$coupon){
  $val_url=trim(get_post_meta($post_id,'_wc_url_apply',true));
  if(isset($_POST['_wc_url_apply'])) $new_url=sanitize_text_field($_POST['_wc_url_apply']); else $new_url='';
  if($val_url!=$new_url)update_post_meta($post_id,'_wc_url_apply',$new_url);
  
  $val_auto=trim(get_post_meta($post_id,'_wc_auto_apply',true));
  if(isset($_POST['_wc_auto_apply'])) $new_auto=sanitize_text_field($_POST['_wc_auto_apply']); else $new_auto='';
  if($val_auto!=$new_auto)update_post_meta($post_id,'_wc_auto_apply',$new_auto);
  
  $val_prefix=trim(get_post_meta($post_id,'_wc_prefix',true));
  $new_prefix=sanitize_text_field($_POST['_wc_prefix']);
  if($val_prefix!=$new_prefix)update_post_meta($post_id,'_wc_prefix',$new_prefix);

  $val_min=trim(get_post_meta($post_id,'_wc_min_qty',true));
  $new_min=intval($_POST['_wc_min_qty']);
  if($new_min==0) $new_min='';
  if($val_min!=$new_min)update_post_meta($post_id,'_wc_min_qty',$new_min);
  
  $val_max=trim(get_post_meta($post_id,'_wc_max_qty',true));
  $new_max=intval($_POST['_wc_max_qty']);
  if($new_max==0) $new_max='';
  if($val_max!=$new_max)update_post_meta($post_id,'_wc_max_qty',$new_max);
  
  $val_ntf=get_post_meta($post_id,'_wc_qty_ntf',true);
  $new_ntf=array_map('sanitize_text_field',$_POST['_wc_qty_ntf']);
  if($val_ntf!=$new_ntf)update_post_meta($post_id,'_wc_qty_ntf',$new_ntf);
}
add_action('woocommerce_coupon_options_save','wac_qty_save_coupon',10,2);