add_action( 'wp_enqueue_scripts', 'wc_remove_lightboxes', 99 );

  /**
   * Remove WooCommerce default prettyphoto lightbox
  */

   function wc_remove_lightboxes() {
     // Styles
     wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
     // Scripts
     wp_dequeue_script( 'prettyPhoto' );
     wp_dequeue_script( 'prettyPhoto-init' );
     wp_dequeue_script( 'fancybox' );
     wp_dequeue_script( 'enable-lightbox' );
  }


/* Customize Product Gallery */

/**
 * Click on thumbnail to view image for single product page gallery. Includes
 * responsive image support using 'srcset' attribute introduced in WP 4.4
 * @link https://make.wordpress.org/core/2015/11/10/responsive-images-in-wordpress-4-4/
 */

add_action( 'wp_footer', 'wc_gallery_override' );

function wc_gallery_override()
{
  // Only include if we're on a single product page.
  if (is_product()) {
  ?>
    <script type="text/javascript">
        ( function( $ ) {

            // Override default behavior
            $('.woocommerce-main-image').on('click', function( event ) {
                event.preventDefault();
            });

            // Find the individual thumbnail images
            var thumblink = $( '.thumbnails .zoom' );

            // Add our active class to the first thumb which will already be displayed
            //on page load.
            thumblink.first().addClass('active');

            thumblink.on( 'click', function( event ) {

                // Override default behavior on click.
                event.preventDefault();

                // We'll generate all our attributes for the new main
                // image from the thumbnail.
                var thumb = $(this).find('img');

                // The new main image url is formed from the thumbnail src by removing
                // the dimensions appended to the file name.
                var photo_fullsize =  thumb.attr('src').replace('-300x300','');

                // srcset attributes are associated with thumbnail img. We'll need to also change them.
                var photo_srcset =  thumb.attr('srcset');

                // Retrieve alt attribute for use in main image.
                var alt = thumb.attr('alt');

                // If the selected thumb already has the .active class do nothing.
                if ($(this).hasClass('active')) {
                    return false;
                } else {

                    // Remove .active class from previously selected thumb.
                    thumblink.removeClass('active');

                    // Add .active class to new thumb.
                    $(this).addClass('active');

                    // Fadeout main image and replace various attributes with those defined above. Once the image is loaded we'll make it visible.
                    $('.woocommerce-main-image img').css( 'opacity', '0' ).attr('src', photo_fullsize).attr('srcset', photo_srcset).attr('alt', alt).load(function() {
                        $(this).animate({ opacity: 1 });
                    });
                    return false;
                    }
                });
        } )( jQuery );
    </script>
<?php
}
}
