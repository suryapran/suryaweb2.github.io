<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="wrap wpnwm-wrap">
	<style type="text/css">
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box.postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.wpnwm-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wpnwm-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.wpos-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.wpos-new-feature{ font-size: 10px; color: #fff; font-weight: bold; background-color: #03aa29; padding:1px 4px; font-style: normal; }
	</style>

	<div class="post-box-container">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">

				<!--How it workd HTML -->
				<div id="post-body-content">
					<div class="meta-box-sortables">
						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'How It Works - Display and Shortcode', 'sp-news-and-widget' ); ?></span>
								</h2>
							</div>

							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr>
											<th>
												<label><?php _e('Getting Started', 'sp-news-and-widget'); ?></label>
											</th>
											<td>
												<ul>
													<li><?php _e('Step-1: This plugin create a News menu tab in WordPress menu with custom post type.', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-2: Go to "News > Add news item tab".', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-3: Add news title, description, category, and image as featured image.', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-4: Repeat this process and add multiple news item.', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-4: To display news category wise you can use category shortcode under "News > News category"', 'sp-news-and-widget'); ?></li>
												</ul>
											</td>
										</tr>

										<tr>
											<th>
												<label><?php _e('How Shortcode Works', 'sp-news-and-widget'); ?></label>
											</th>
											<td>
												<ul>
													<li><?php _e('Step-1. Create a page like Our News OR Latest News.', 'sp-news-and-widget'); ?></li>
													<li><?php _e('<b>Please make sure that Permalink link should not be "/news" Otherwise all your news will go to archive page. You can give it other name like "/ournews, /latestnews etc"</b>', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-2. Put below shortcode as per your need.', 'sp-news-and-widget'); ?></li>
												</ul>
											</td>
										</tr>

										<tr>
											<th>
												<label><?php _e('All Shortcodes', 'sp-news-and-widget'); ?></label>
											</th>
											<td>
												<span class="wpos-copy-clipboard wpnwm-shortcode-preview">[sp_news grid="list"]</span> – <?php _e('News in List View', 'sp-news-and-widget'); ?> <br />
												<span class="wpos-copy-clipboard wpnwm-shortcode-preview">[sp_news grid="1"]</span> – <?php _e('Display News in grid 1', 'sp-news-and-widget'); ?> <br />
												<span class="wpos-copy-clipboard wpnwm-shortcode-preview">[sp_news grid="2"]</span> – <?php _e('Display News in grid 2', 'sp-news-and-widget'); ?> <br />
												<span class="wpos-copy-clipboard wpnwm-shortcode-preview">[sp_news grid="3"]</span> – <?php _e('Display News in grid 3', 'sp-news-and-widget'); ?>
											</td>
										</tr>
									</tbody>
								</table>
							</div><!-- .inside -->
						</div><!-- #general -->

						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'Gutenberg Support', 'sp-news-and-widget' ); ?></span>
								</h2>
							</div>
							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr>
											<th>
												<label><?php _e('How it Work', 'sp-news-and-widget'); ?>:</label>
											</th>
											<td>
												<ul>
													<li><?php _e('Step-1. Go to the Gutenberg editor of your page.', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-2. Search "news" keyword in the Gutenberg block list.', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-3. Add any block of news and you will find its relative options on the right end side.', 'sp-news-and-widget'); ?></li>
												</ul>
											</td>
										</tr>											
									</tbody>
								</table>
							</div><!-- .inside -->
						</div><!-- #general -->

						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'Need Support?', 'sp-news-and-widget' ); ?></span>
								</h2>
							</div>
							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr>
											<td>
												<p><?php _e('Check plugin document for shortcode parameters and demo for designs.', 'sp-news-and-widget'); ?></p> <br/>
												<a class="button button-primary" href="https://docs.wponlinesupport.com/wp-news-and-scrolling-widgets/" target="_blank"><?php _e('Documentation', 'sp-news-and-widget'); ?></a>
												<a class="button button-primary" href="https://demo.wponlinesupport.com/sp-news/" target="_blank"><?php _e('Demo for Designs', 'sp-news-and-widget'); ?></a>
											</td>
										</tr>											
									</tbody>
								</table>
							</div><!-- .inside -->
						</div><!-- #general -->

						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'Help to improve this plugin!', 'sp-news-and-widget' ); ?></span>
								</h2>
							</div>
							<div class="inside">
								<p><?php _e( 'Enjoyed this plugin? You can help by rate this plugin ', 'sp-news-and-widget' ); ?><a href="https://wordpress.org/support/plugin/sp-news-and-widget/reviews/#new-post" target="_blank"><?php _e( '5 stars!', 'sp-news-and-widget' ); ?></a></p>
							</div><!-- .inside -->
						</div><!-- #general -->
					</div><!-- .meta-box-sortables -->
				</div><!-- #post-body-content -->

				<!--Upgrad to Pro HTML -->
				<div id="postbox-container-1" class="postbox-container">
					<div class="meta-box-sortables">
						<div class="postbox wpos-pro-box">

							<h3 class="hndle">
								<span><?php _e( 'Upgrate to Pro', 'sp-news-and-widget' ); ?></span>
							</h3>
							<div class="inside">
								<ul class="wpos-list">
									<li>120+ stunning and cool designs</li>
									<li>6 shortcodes</li>
									<li>50 Designs for News Grid Layout.</li>
									<li>45 Designs for News Slider/Carousel Layout.</li>
									<li>8 Designs for News List View.</li>
									<li>3 Designs News Grid Box.</li>
									<li>8 Designs News Grid Box Slider.</li>
									<li>WPBakery Page Builder Supports</li>
									<li>Gutenberg, Elementor, Beaver and SiteOrigin Page Builder Support. <span class="wpos-new-feature">New</span></li>
									<li>Divi Page Builder Native Support. <span class="wpos-new-feature">New</span></li>
									<li>Fusion (Avada) Page Builder Native Support. <span class="wpos-new-feature">New</span></li>
									<li>WP Templating Features</li>
									<li>News Ticker.</li>
									<li>7 different types of Latest News widgets.</li>
									<li>Recent News Slider</li>
									<li>Recent News Carousel</li>
									<li>Recent News in Grid view</li>
									<li>Create a News Page OR News website</li>
									<li>Custom Read More link for News Post</li>
									<li>News display with categories</li>
									<li>Drag & Drop feature to display News post in your desired order and other 6 types of order parameter</li>
									<li>Publicize' support with Jetpack to publish your News post on your social network</li>
									<li>Custom CSS</li>
									<li>100% Multi language</li>
								</ul>
								<div class="upgrade-to-pro"><?php echo sprintf( __( 'Gain access to <strong>WP News and Scrolling Widgets</strong> included in <br /><strong>Essential Plugin Bundle', 'sp-news-and-widget' ) ); ?></div>
								<a class="button button-primary wpos-button-full" href="https://www.wponlinesupport.com/wp-plugin/sp-news-and-scrolling-widgets/?ref=WposPratik&utm_source=WP&utm_medium=News&utm_campaign=Upgrade-PRO" target="_blank"><?php _e('Go Premium ', 'sp-news-and-widget'); ?></a>
								<p><a class="button button-primary wpos-button-full" href="https://demo.wponlinesupport.com/prodemo/news-plugin-pro/" target="_blank"><?php _e('View PRO Demo ', 'sp-news-and-widget'); ?></a></p>
							</div><!-- .inside -->
						</div><!-- #general -->
					</div><!-- .meta-box-sortables -->
				</div><!-- #post-container-1 -->

			</div><!-- #post-body -->
		</div><!-- #poststuff -->
	</div>
</div>