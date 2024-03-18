<?php
// add to cart configure

$product_id = alarnd_product_id();
$variation_id = alarnd_variation_id();

$is_position_enable = get_field( 'art_position_enable', $product_id );
$art_positions = get_field( 'art_positions', $product_id );
$group_uniqid = (isset( $_GET['group_uniqid'] ) && ! empty( $_GET['group_uniqid'] )) ? $_GET['group_uniqid'] : '';
?>
<main id="main" class="site-main" role="main">
    <div class="alarnd--configure-wrap">
        <div class="alarnd--configure-header">
            <h2><?php esc_html_e( 'ךלש תונמאה תוריצי תא הלעה', 'hello-elementor' ); ?></h2>
            <span><?php esc_html_e( 'רושיאל הימדה ךל חלשנ הספדהל רבעמה ינפל', 'hello-elementor' ); ?><i class="fa fa-exclamation" aria-hidden="true"></i></span>
        </div>
        <form action="" class="alarnd--configure-cart">
            <input type="hidden" name="alarnd_group_uniqid" value="<?php echo esc_attr( $group_uniqid ); ?>">
            <div class="alarnd--upload-wrap">
                <label for="alarnd_artwork_file">
                    <span><?php esc_html_e('ץבוק רחב', 'hello-elementor'); ?>...</span>
                    <input type="file" name="alarnd--artwork-file" class="alarnd_artwork_file" id="alarnd_artwork_file">
                    <input type="hidden" class="alarnd_artwork_id" name="alarnd_artwork_id">
                    
                </label>
                <div class="artwork-input artwork-input-first">
                    <input aria-hidden="true" tabindex="-1" type="text" readonly="" class="alarnd--artwork-icon" value="" style="cursor: pointer;">
                </div>
            </div>

            <div class="addition_file_check">
                <label><input type="checkbox" class="upload_another_one" /> <?php esc_html_e( 'Click to upload additional file', 'hello-elementor' ); ?></label>
            </div>

            <div class="alarnd-second-upload-wrap">
                <div class="alarnd--upload-wrap alarnd-second-upload2">
                    <label for="alarnd_artwork_file_second">
                        <span><?php esc_html_e('ץבוק רחב', 'hello-elementor'); ?>...</span>
                        <input type="file" name="alarnd--artwork-file" class="alarnd_artwork_file alarnd_artwork_file_second" id="alarnd_artwork_file_second">
                        <input type="hidden" class="alarnd_artwork_id" name="alarnd_artwork_id2">
                    </label>
                    <div class="artwork-input artwork-input-second">
                        <input aria-hidden="true" tabindex="-1" type="text" readonly="" class="alarnd--artwork-icon" value="" style="cursor: pointer;">
                    </div>
                </div>
            </div>
            <div class="alarnd--progress-bar">
                <div class="artwork-upload-progress"><div class="progress-bar"></div></div>
            </div>

            <?php
            if( ! empty( $is_position_enable ) && ! empty( $art_positions ) ) : ?>
            <div class="alarnd--artwork-position-wrap">
                <div class="alarnd--artwork-position">
                    <?php foreach( (array) $art_positions as $key => $position ) :
                        $thumbnail = isset( $position['thumbnail'] ) && ! empty( $position['thumbnail'] ) ? absint( $position['thumbnail'] ) : '';    
                        $title = isset( $position['title'] ) && ! empty( $position['title'] ) ? sanitize_text_field( $position['title'] ) : '';    
                        $max = isset( $position['max'] ) && ! empty( $position['max'] ) ? sanitize_text_field( $position['max'] ) : '';
                        $special = isset( $position['enable_special'] ) && ! empty( $position['enable_special'] ) ? boolval( $position['enable_special'] ) : '';
                        $description = isset( $position['special_description'] ) && ! empty( $position['special_description'] ) ? sanitize_text_field( $position['special_description'] ) : '';
                    ?>
                    <div class="alarn--single-artwork-pos" data-key="<?php echo $key; ?>">
                        <div class="alarnd--single-art-inner">
                            <?php if( true === $special ) : ?>
                                <div class="alarnd--art-ribbon">
                                    <i class="fa fa-star"></i>
                                </div>
                            <?php endif; ?>
                            <label for="artwork_upload" class="single-artwork-position">
                                <?php if( !empty( $thumbnail ) ) {
                                    echo wp_get_attachment_image( $thumbnail, 'full' );
                                }
                                ?>
                                <div class="artwork-position-details">
                                    <p ><?php echo esc_html( $title ); ?></p>
                                    <small><?php echo esc_html( $max ); ?></small>
                                </div>
                            </label>
                        </div>
                        <?php if( true === $special && ! empty( $description ) ) : ?>
                            <div class="alarnd--art-description">
                                <p><?php echo esc_html( $description ); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <input type="hidden" name="art_position">
            <?php endif; ?>

            <div class="alarnd--configue-bottom">
                <div class="alarnd--instructions-form">
                    <h4><?php esc_html_e('הכרדה', 'hello-elementor') ; ?> <span>(<?php esc_html_e( 'ילנויצפוא', 'hello-elementor' ); ?>)</span></h4>
                    <textarea name="alarnd_instruction" id="" cols="10" rows="3" placeholder="<?php esc_attr_e('ךלש היחנהה תא בותכ', 'hello-elementor'); ?>"></textarea>
                    <div class="alarnd--submit-wrap">
                        <input type="hidden" name="product_id" value="<?php echo esc_attr( $product_id ); ?>">
                        <input type="hidden" name="variation_id" value="<?php echo esc_attr( $variation_id ); ?>">

                        <div class="alarnd--configure-skip">
                            <span><?php printf( '' . esc_html__( 'or,', 'hello-elementor' ) . ' <a href="%1$s">' . __( 'skip this step & email artwork later.', 'hello-elementor' ) . '</a>',  esc_url( wc_get_cart_url() ) ); ?></span>
                        </div>
                        <button type="submit" class="alarnd--submit-btn alarnd--configure-submit"><?php esc_html_e('ךיִׁשמַהְל', 'hello-elementor'); ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
