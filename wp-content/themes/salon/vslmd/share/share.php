<!-- Twitter script: -->
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
 
 
<!-- Facebook HTML5 script: -->
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
 
 
<!-- Google+ script: -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
 
<!-- LinkedIn script: -->
<script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>

<?php 
	
function vslmd_social_sharing_buttons($content) {
	if(is_singular() && !is_page()){

		$options = get_option('vslmd_options');
		$post_type_share_position = (empty($options['post_type_share_position'])) ? 'text-center' : $options['post_type_share_position'];
	
		// Get current page URL 
		$vslmdURL = urlencode(get_permalink());
 
		// Get current page title
		$vslmdTitle = str_replace( ' ', '%20', get_the_title());
 
		// Construct sharing URL without using any script
		$twitterURL = 'https://twitter.com/intent/tweet?text='.$vslmdTitle.'&amp;url='.$vslmdURL.'&amp;via=Visualmodo';
		$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$vslmdURL;
		$googleURL = 'https://plus.google.com/share?url='.$vslmdURL;
		$linkedInURL = 'https://www.linkedin.com/shareArticle?mini=true&url='.$vslmdURL.'&amp;title='.$vslmdTitle;
 
		// Add sharing button at the end of page/page content
		$content .= '<div class="'.$post_type_share_position.' vslmd-social">';
		$content .= '<a class="vslmd-link vslmd-facebook" href="'.$facebookURL.'" target="_blank"><i class="fa fa-facebook"></i></a>';
		$content .= '<a class="vslmd-link vslmd-googleplus" href="'.$googleURL.'" target="_blank"><i class="fa fa-google-plus"></i></a>';
		$content .= '<a class="vslmd-link vslmd-twitter" href="'. $twitterURL .'" target="_blank"><i class="fa fa-twitter"></i></a>';
		$content .= '<a class="vslmd-link vslmd-linkedin" href="'.$linkedInURL.'" target="_blank"><i class="fa fa-linkedin"></i></a>';
		$content .= '</div>';
		
		return $content;
	}else{
		// if not a post/page then don't include sharing button
		return $content;
	}
};
add_filter( 'the_content', 'vslmd_social_sharing_buttons');

?>