<?php
add_action( 'after_setup_theme', 'blankslate_setup' );

function blankslate_setup() {
  load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
  add_theme_support( 'title-tag' );
  add_theme_support( 'automatic-feed-links' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'html5', array( 'search-form' ) );
  global $content_width;
  if ( ! isset( $content_width ) ) { $content_width = 1920; }
  register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'blankslate' ) ) );
}
add_action( 'wp_enqueue_scripts', 'blankslate_load_scripts' );

function blankslate_load_scripts() {
  wp_enqueue_style( 'blankslate-style', get_stylesheet_uri() );
  wp_enqueue_script( 'custom-scripts', get_template_directory_uri() . '/assets/js/scripts.js' );
}
add_action( 'wp_footer', 'blankslate_footer_scripts' );

function blankslate_footer_scripts() {
}
add_filter( 'document_title_separator', 'blankslate_document_title_separator' );

function blankslate_document_title_separator( $sep ) {
  $sep = '|';
  return $sep;
}
add_filter( 'the_title', 'blankslate_title' );

function blankslate_title( $title ) {
  if ( $title == '' ) {
    return '...';
  } else {
    return $title;
  }
}
add_filter( 'the_content_more_link', 'blankslate_read_more_link' );

function blankslate_read_more_link() {
  if ( ! is_admin() ) {
    return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">...</a>';
  }
}
add_filter( 'excerpt_more', 'blankslate_excerpt_read_more_link' );

function blankslate_excerpt_read_more_link( $more ) {
  if ( ! is_admin() ) {
    global $post;
    return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">...</a>';
  }
}
add_filter( 'intermediate_image_sizes_advanced', 'blankslate_image_insert_override' );

function blankslate_image_insert_override( $sizes ) {
  unset( $sizes['medium_large'] );
  return $sizes;
}
add_action( 'widgets_init', 'blankslate_widgets_init' );

function blankslate_widgets_init() {
  register_sidebar( array(
  'name' => esc_html__( 'Sidebar Widget Area', 'blankslate' ),
  'id' => 'primary-widget-area',
  'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
  'after_widget' => '</li>',
  'before_title' => '<h3 class="widget-title">',
  'after_title' => '</h3>',
  ) );
}
add_action( 'wp_head', 'blankslate_pingback_header' );

function hp_footer_one() {
  register_sidebar( array(
      'name' => __( 'Homepage Footer: One', 'smallenvelop' ),
      'id' => 'footer-one',
      'before_widget' => '<div class="footer-column">',
      'after_widget' => '</div>',
      'before_title' => '<h1>',
      'after_title' => '</h1>',
  ) );
}
add_action( 'widgets_init', 'hp_footer_one' );

function hp_footer_two() {
  register_sidebar( array(
      'name' => __( 'Homepage Footer: Two', 'smallenvelop' ),
      'id' => 'footer-two',
      'before_widget' => '<div class="footer-column">',
      'after_widget' => '</div>',
      'before_title' => '<h1>',
      'after_title' => '</h1>',
  ) );
}
add_action( 'widgets_init', 'hp_footer_two' );

function sp_footer_one() {
  register_sidebar( array(
      'name' => __( 'Subpage Footer: One', 'smallenvelop' ),
      'id' => 'footer-three',
      'before_widget' => '<div class="footer-column">',
      'after_widget' => '</div>',
      'before_title' => '<h2>',
      'after_title' => '</h2>',
  ) );
}
add_action( 'widgets_init', 'sp_footer_one' );

function sp_footer_two() {
  register_sidebar( array(
      'name' => __( 'Subpage Footer: Two', 'smallenvelop' ),
      'id' => 'footer-four',
      'before_widget' => '<div class="footer-column">',
      'after_widget' => '</div>',
      'before_title' => '<h2>',
      'after_title' => '</h2>',
  ) );
}
add_action( 'widgets_init', 'sp_footer_two' );

function blankslate_pingback_header() {
  if ( is_singular() && pings_open() ) {
    printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
  }
}
add_action( 'comment_form_before', 'blankslate_enqueue_comment_reply_script' );

function blankslate_enqueue_comment_reply_script() {
  if ( get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }
}

function blankslate_custom_pings( $comment ) {
  ?>
  <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
  <?php
}
add_filter( 'get_comments_number', 'blankslate_comment_count', 0 );

function blankslate_comment_count( $count ) {
  if ( ! is_admin() ) {
    global $id;
    $get_comments = get_comments( 'status=approve&post_id=' . $id );
    $comments_by_type = separate_comments( $get_comments );
    return count( $comments_by_type['comment'] );
  } else {
    return $count;
  }
}

function add_file_types_to_uploads($file_types){
  $new_filetypes = array();
  $new_filetypes['svg'] = 'image/svg+xml';
  $file_types = array_merge($file_types, $new_filetypes );
  return $file_types;
}
add_filter('upload_mimes', 'add_file_types_to_uploads');

// CUSTOM FUNCTIONS BELOW

function welcome_section($atts) {
	ob_start();
	$data = shortcode_atts(array(
		'video_on_off' => 'on'
	),$atts);
	?>
	<div id="hero-container" <?php if ($data['video_on_off'] == 'off') { ?> style="background-image: url('/wp-content/uploads/2020/03/homepage-slider.png');" <?php } ?>>
		<?php if ($data['video_on_off'] == 'on') { ?>
		<div id="video-text">
			<h1 class="video-thin-text hero-fade">The</h1>
			<h1 id="video-bold-text" class="hero-fade">best care</h1>
			<h1 class="video-thin-text hero-fade">for your</h1>
			<h1 id="video-italics-text" class="hero-fade"><span class="video-underline">best</span> f<span class="video-underline">riend</span></span></h1>
		</div>
		<div style="width: 100%" class="wp-video">
			<div id="video-overlay"></div>
			<video muted autoplay="autoplay" loop class="wp-video-shortcode" id="video-697-2" width="100%" height="100%" preload="metadata"><source type="video/mp4" src="/wp-content/uploads/2020/04/hero_stock_video.mp4?_=2" /><a href="/wp-content/uploads/2020/04/hero_stock_video.mp4">/wp-content/uploads/2020/04/hero_stock_video.mp4</a></video>
		</div>
		<?php } ?>
	</div>
	<div id="welcome-section">
		<div id="three-callouts">
			<a href="/about/veterinarians/" style="background-image: url('/wp-content/uploads/2020/03/widget-image-1.png')" class="callout">
				<div class="callout-text">
					<h3>Caring & Compassionate</h3>
					<h2>Our<br>Team</h2>
				</div>
			</a>
			<a href="/services/" style="background-image: url('/wp-content/uploads/2020/03/widget-image-2.png')" class="callout">
				<div class="callout-text">
					<h3>Comprehensive Care</h3>
					<h2>Our<br>Services</h2>
				</div>
			</a>
			<a href="/appointment/" style="background-image: url('/wp-content/uploads/2020/03/widget-image-3.png')" class="callout">
				<div class="callout-text">
					<h3>Quick & Convenient</h3>
					<h2>Request an<br>Appointment</h2>
				</div>
			</a>
		</div>
		<div id="about-area">
			<div class="inner-wrapper">
				<div class="about-section" id="first-about-section">
					<div id="about-intro" class="about-animation-text">
						<h3>Welcome to</h3>
						<h2>Awesome Vet<br>Animal Hospital</h2>
						<h3>Optional Tagline</h3>
					</div>
					<img id="icons-image" src="/wp-content/uploads/2020/04/animal-icons.png" alt="Animal Icons">
				</div>
				<div class="about-section">
					<p class="about-section-text about-animation-text">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
				</div>
				<div class="about-section">
					<p class="about-section-text about-animation-text">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
				</div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('welcome_section','welcome_section');

function homepage_services() {
	ob_start();
	?>
	<div id="homepage-services">
		<div class="inner-wrapper">
			<div class="fifty-percent">
				<div id="hp-services-intro">
					<div id="hp-services-intro-container">
						<h2 id="hp-services-title">Looking for veterinary<br>services in your town?</h2>
					</div>
					<p id="hp-services-text">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
				</div>
			</div>
			<div class="fifty-percent">
				<h3 id="services-title">Our Services</h3>
				<div id="services-box">
					<div id="services-inner">
						<?php
						$args = array(
							'category_name' => 'Services',
							'posts_per_page' => '-1'
						);
						$posts_array = new WP_Query($args);
						if ( $posts_array -> have_posts() ) :
							while ( $posts_array -> have_posts() ) :
								$posts_array -> the_post();
								$id = get_the_ID();
								$link = get_permalink();
								$title = get_the_title();
						?>
							<a href="<?php echo $link ?>" class="service">
								<i class="fas fa-paw"></i>
								<?php echo $title ?>
							</a>
						<?php endwhile; endif; ?>
					</div>
					<a href="/services/" class="blue-button-container">
						<div class="blue-button">
							Our Services
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('homepage_services','homepage_services');

function team_section() {
	ob_start();
	?>
	<svg id="team-border-one" version="1.1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
		 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1803.9 214.6"
		 style="enable-background:new 0 0 1803.9 214.6;" xml:space="preserve">
	<style type="text/css">
		.st0{display:none;}
		.st1{display:inline;}
		.st2{fill:url(#SVGID_3_);}
		.st3{clip-path:url(#SVGID_5_);}
		.st4{fill:#D7E1E9;}
		.st5{fill:#C5D6E1;}
		.st6{fill:#95D9F1;}
		.st7{clip-path:url(#SVGID_7_);}
		.st8{fill:none;stroke:#014051;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
		.st9{fill:#014051;}
		.st10{opacity:0.17;fill:#308BAC;}
		.st11{fill:#308BAC;}
		.st12{opacity:0.8;}
		.st13{fill:#E5EBF0;}
		.st14{fill:#D9E3EA;}
		.st15{fill:#E7ECF1;}
		.st16{fill:#C9D8E3;}
		.st17{fill:#FFFFFF;}
		.st18{fill:url(#SVGID_12_);}
		.st19{opacity:0.44;fill:#014051;}
		.st20{fill:url(#SVGID_17_);}
		.st21{fill:url(#SVGID_20_);}
		.st22{fill:url(#SVGID_23_);}
		.st23{fill:url(#SVGID_30_);}
		.st24{fill:url(#SVGID_31_);}
		.st25{fill:#58595B;}
		.st26{opacity:0.44;fill:#58595B;}
	</style>
	<metadata>
		<sfw  xmlns="&ns_sfw;">
			<slices></slices>
			<sliceSourceBounds  bottomLeftOrigin="true" height="7352.9" width="7006.4" x="-100" y="-7352.9"></sliceSourceBounds>
			<optimizationSettings>
				<targetSettings  fileFormat="PNG24Format" targetSettingsID="0">
					<PNG24Format  filtered="false" interlaced="false" matteColor="#FFFFFF" noMatteColor="false" transparency="true">
						</PNG24Format>
				</targetSettings>
			</optimizationSettings>
		</sfw>
	</metadata>
	<g id="template_references" class="st0">
	</g>
	<g id="Layer_2">
		<polygon class="st4 light-gray-line-one" points="1132,110.2 1132,110.2 903.9,81.7 1803.9,31.5 1803.9,110.2 1803.9,110.2 1803.9,188.9 903.9,81.7
			1132,110.2 	"/>
		<polygon id="gray-line-one" class="st5" points="1803.9,0.4 903.9,81.7 903.8,81.8 903.6,81.7 0,0 0,158.5 984.1,157.6 984,157.5 1803.9,158.2 	"/>
		<path id="light-gray-line-one" class="st4 light-gray-line-one" d="M902,81.6L0,31.2v79v0v79L901.9,81.9l0,0l-227.4,28.4l0,0l0,0L902,81.9l227.4,28.4l0,0l0,0L902,81.9l0,0
			l901.9,107.5v-79v0v-79L902,81.6z M902,81.9L902,81.9L902,81.9L902,81.9L902,81.9z"/>
		<polygon class="st6" points="0,166.5 902,81.7 1803.9,166.5 1803.9,214.6 0,214.6 	"/>
	</g>
	</svg>
	<div id="meet-the-team">
		<div class="inner-wrapper">
			<div class="fifty-percent">
				<img id="team-image" src="/wp-content/uploads/2020/04/meet-our-team.png" alt="Meet Our Team">
			</div>
			<div class="fifty-percent">
				<h3 id="team-title">Meet Our Team</h3>
				<p id="team-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
				<a href="/about/veterinarians/" class="blue-button-container">
					<div class="blue-button">
						Get to know our team
					</div>
				</a>
			</div>
		</div>
	</div>
	<svg id="team-border-two" version="1.1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
		 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1803.9 214.6"
		 style="enable-background:new 0 0 1803.9 214.6;" xml:space="preserve">
	<style type="text/css">
		.st0{display:none;}
		.st1{display:inline;}
		.st2{fill:url(#SVGID_3_);}
		.st3{clip-path:url(#SVGID_5_);}
		.st4{fill:#D7E1E9;}
		.st5{fill:#C5D6E1;}
		.st6{fill:#95D9F1;}
		.st7{clip-path:url(#SVGID_7_);}
		.st8{fill:none;stroke:#014051;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
		.st9{fill:#014051;}
		.st10{opacity:0.17;fill:#308BAC;}
		.st11{fill:#308BAC;}
		.st12{opacity:0.8;}
		.st13{fill:#E5EBF0;}
		.st14{fill:#D9E3EA;}
		.st15{fill:#E7ECF1;}
		.st16{fill:#C9D8E3;}
		.st17{fill:#FFFFFF;}
		.st18{fill:url(#SVGID_12_);}
		.st19{opacity:0.44;fill:#014051;}
		.st20{fill:url(#SVGID_17_);}
		.st21{fill:url(#SVGID_20_);}
		.st22{fill:url(#SVGID_23_);}
		.st23{fill:url(#SVGID_30_);}
		.st24{fill:url(#SVGID_31_);}
		.st25{fill:#58595B;}
		.st26{opacity:0.44;fill:#58595B;}
	</style>
	<metadata>
		<sfw  xmlns="&ns_sfw;">
			<slices></slices>
			<sliceSourceBounds  bottomLeftOrigin="true" height="7352.9" width="7006.4" x="-100" y="-7352.9"></sliceSourceBounds>
			<optimizationSettings>
				<targetSettings  fileFormat="PNG24Format" targetSettingsID="0">
					<PNG24Format  filtered="false" interlaced="false" matteColor="#FFFFFF" noMatteColor="false" transparency="true">
						</PNG24Format>
				</targetSettings>
			</optimizationSettings>
		</sfw>
	</metadata>
	<g id="template_references-two" class="st0">
	</g>
	<g id="Layer_3">
		<polygon class="st4 light-gray-line-two" points="671.9,104.3 671.9,104.3 900,132.8 0,183.1 0,104.3 0,104.3 0,25.6 900,132.8 671.9,104.3 	"/>
		<polygon id="gray-line-two" class="st5" points="819.8,57 819.9,57.1 0,56.4 0,214.2 900,132.8 900.1,132.7 900.3,132.9 1803.9,214.6 1803.9,56.1 	"/>
		<path id="light-gray-line-two" class="st4 light-gray-line-two" d="M902,132.7L902,132.7l227.3-28.4l0,0l0,0L902,132.7l-227.4-28.4l0,0l0,0L902,132.7L902,132.7L0,25.2v79v0v79
			L902,133l902,50.3v-79v0v-79L902,132.7z M902,132.7L902,132.7L902,132.7L902,132.7L902,132.7z"/>
		<polygon class="st6" points="1803.9,48 902,132.8 0,48 0,0 1803.9,0 	"/>
	</g>
	</svg>
	<?php
	return ob_get_clean();
}
add_shortcode('team_section','team_section');

function appointment_area($atts) {
	ob_start();
	$data = shortcode_atts(array(
		'background_image' => '/wp-content/uploads/2020/03/homepage-appointment-image.png',
		'margin_top' => '-5%',
	),$atts);
	?>
	<div class="appointment-area" style="background-image: url('<?php echo $data['background_image']?>'); margin-top: <?php echo $data['margin_top']?>;">
		<div class="inner-wrapper">
			<div class="appointment-container" <?php if ( is_front_page() ) { ?> id="hp-appointment-container" <?php } else { ?> id="sp-appointment-container" <?php } ?>>
				<h2>Make an appointment<br>with us today!</h2>
				<div class="appointment-box">
					<a href="tel:555-555-5555" class="blue-button-container">
						<div class="dark-blue-button">
							Call us! 555-555-5555
						</div>
					</a>
					<a href="/appointment/" class="blue-button-container">
						<div class="dark-blue-button">
							Book online today
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('appointment_area','appointment_area');

function team_page() {
	ob_start();
	?>
	<div id="team-page">
		<?php
		$args = array(
			'category_name' => 'Team',
			'posts_per_page' => '-1',
			'order' => 'ASC'
			);
			$loop = new WP_Query($args);
			if ($loop->have_posts()) : while ($loop->have_posts()) : $loop->the_post();
			$id = get_the_ID();
			$img = get_the_post_thumbnail_url($id);
			$name = get_the_title();
			$content = get_the_content();
      $position = $loop->current_post;
      $remainder = $position % 2;
			?>
			<div class="team-container<?php if ($remainder != 0) { ?> odd-background<?php } ?>">
        <div class="team-container-inner">
          <div class="team-photo">
            <img src="<?php echo $img ?>" alt="<?php echo $name ?>">
          </div>
          <div class="team-text">
            <h2><?php echo $name ?></h2>
            <p><?php echo $content ?></p>
          </div>
        </div>
			</div>
		<?php endwhile; endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('team_page','team_page');

function subpage_services() {
	ob_start();
	?>
	<div id="subpage-services">
		<?php
		$args = array(
			'category_name' => 'Services',
			'posts_per_page' => '-1'
		);
		$posts_array = new WP_Query($args);
		if ( $posts_array -> have_posts() ) :
			while ( $posts_array -> have_posts() ) :
				$posts_array -> the_post();
				$id = get_the_ID();
				$img = get_the_post_thumbnail_url($id);
				$link = get_permalink();
				$title = get_the_title();
		?>
			<a href="<?php echo $link ?>" class="service-box">
				<div class="inner-service-box">
					<div class="service-overlay"></div>
					<img src="<?php echo $img ?>" alt="<?php echo $title ?>">
					<div class="service-title-box">
						<h2><?php echo $title ?></h2>
						<p class="service-learn-more">Learn More</p>
					</div>
				</div>
			</a>
		<?php endwhile; endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('subpage_services','subpage_services');

function contact_us() {
	ob_start();
	?>
	<div id="contact-us">
		<div id="contact-map">
			<iframe title="Map of <?php echo get_bloginfo('name') ?>" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d11181.039447104198!2d-122.6753!3d45.524976!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6e8ef0690debfad0!2siVET360!5e0!3m2!1sen!2sus!4v1586451337492!5m2!1sen!2sus" width="100%" height="650" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
		</div>
		<div id="contact-info">
			<div class="visit-us">
				<h2><?php echo get_bloginfo('name') ?></h2>
				<a href="https://g.page/iVET360?share" target="_blank" rel=”noopener”>222 NW 5th Ave #4, Portland, OR 97209</a>
				<div class="contact-box">
					<a href="tel:555-555-5555"><span class="bold">Phone:</span> 555-555-5555</a>
					<a href="tel:666-666-6666"><span class="bold">Fax:</span> 666-666-6666</a>
					<a href="mailto:info@awesomevet.com">info@awesomevet.com</a>
				</div>
				<div class="hours-box">
					<p>Mon-Fri: 8:00am - 5:00pm</p>
					<p>Sat-Sun: 9:00am - 3:00pm</p>
				</div>
				<div class="newsletter">
					<p>We'd love to hear from you</p>
					<?php echo do_shortcode('[gravityform id="3" title="false" description="false"]')?>
				</div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('contact_us','contact_us');

function review_block($atts) {
	ob_start();
	$data = shortcode_atts(array(
		'link' => '#',
		'icon' => 'fab fa-facebook-f',
		'name' => 'Facebook',
		'color' => '#3D5D99'
	),$atts);
	?>
	<a class="review-block" href="<?php echo $data['link'] ?>" target="_blank" rel=”noopener” style="background-color: <?php echo $data['color'] ?>">
		<i class="<?php echo $data['icon'] ?>"></i>
		<p>Review us on <?php echo $data['name'] ?></p>
	</a>
	<?php
	return ob_get_clean();
}
add_shortcode('review_block','review_block');
