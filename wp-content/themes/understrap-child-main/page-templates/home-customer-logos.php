<div class="container-fluid section_logos bg-gray-fb" id="customers">
<?php 
    $repeater_data = get_field('customers_rep_logos');
    if ($repeater_data) { ?>
        <div class="row max-1360 grid media justify-content-end gx-lg-2">
            <div class="col-lg-1"></div>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 d-flex">
                <div class="media-item-contents grid-item">
                    <?php 
                    $image = $repeater_data[0]['image'];
                    $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                    ?>
                    <img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($alt_text); ?>">                
                </div>
            </div>
            <div class="col-lg-1"></div>
        </div>    

        <!-- Render next 2-4 entries -->
        <div class="row max-1360 grid media gx-lg-2">
            <div class="col-lg-1"></div>
            <?php for ($i=1; $i<=1; $i++) { ?>
              <div class="col-6 col-sm-4 col-md-3 col-lg-2 d-flex">
                  <div class="media-item-contents grid-item">
                      <?php 
                      $image = $repeater_data[$i]['image'];
                      $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                      ?>
                      <img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($alt_text); ?>">
                  </div>
              </div>
            <?php } ?>
            <?php for ($i=2; $i<=2; $i++) { ?>
              <div class="col-6 col-sm-4 col-md-3 col-lg-2 offset-lg-2 d-flex">
                  <div class="media-item-contents grid-item">
                    <?php 
                    $image = $repeater_data[$i]['image'];
                    $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                    ?>
                    <img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($alt_text); ?>">
                 </div>
              </div>
            <?php } ?>
            <?php for ($i=3; $i<=3; $i++) { ?>
              <div class="col-6 col-sm-4 col-md-3 col-lg-2 d-flex">
                  <div class="media-item-contents grid-item">
                      <?php 
                      $image = $repeater_data[$i]['image'];
                      $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                      ?>
                      <img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($alt_text); ?>">
                  </div>
              </div>
            <?php } ?>
            <div class="col-lg-1"></div>
        </div>

        <div class="row max-1360 grid media gx-lg-2">
            <div class="col-lg-3"></div>
            <?php for ($i=4; $i<=4; $i++) { ?>
              <div class="col-6 col-sm-4 col-md-3 col-lg-2 d-flex">
                  <div class="media-item-contents grid-item">
                     <?php 
                      $image = $repeater_data[$i]['image'];
                      $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                      ?>
                      <img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($alt_text); ?>">
                  </div>
              </div>
            <?php } ?>
            <?php for ($i=5; $i<=7; $i++) { ?>
              <div class="col-6 col-sm-4 col-md-3 col-lg-2 d-flex">
                  <div class="media-item-contents grid-item">
                  <?php 
                      $image = $repeater_data[$i]['image'];
                      $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                      ?>
                      <img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($alt_text); ?>">
                  </div>
              </div>
            <?php } ?>
            <div class="col-lg-1"></div>
        </div>

        <div class="row max-1360 grid media gx-lg-2 d-none d-md-flex">
            <div class="col-lg-1"></div>
            <?php for ($i=8; $i<=12; $i++) { ?>
              <div class=" col-md-3 col-lg-2 d-flex">
                  <div class="media-item-contents grid-item">
                  <?php 
                      $image = $repeater_data[$i]['image'];
                      $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                      ?>
                      <img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($alt_text); ?>">
                  </div>
              </div>
            <?php } ?>
            <div class="col-lg-1"></div>
        </div>

        <div class="row max-1360 grid media gx-lg-2 d-none d-md-flex">
            <div class="col-lg-1"></div>
            <?php for ($i=13; $i<=17; $i++) { ?>
              <div class=" col-md-3 col-lg-2 d-flex">
                  <div class="media-item-contents grid-item">
                  <?php 
                      $image = $repeater_data[$i]['image'];
                      $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                      ?>
                      <img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($alt_text); ?>">
                  </div>
              </div>
            <?php } ?>
            <div class="col-lg-1"></div>
        </div>    
        
        <div class="row max-1360 grid media gx-lg-2 d-none d-md-flex">
            <div class="col-lg-1"></div>
            <?php for ($i=18; $i<=19; $i++) { ?>
              <div class="col-md-3 col-lg-2 d-flex">
                  <div class="media-item-contents grid-item">
                  <?php 
                      $image = $repeater_data[$i]['image'];
                      $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                      ?>
                      <img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($alt_text); ?>">
                  </div>
              </div>
            <?php } ?>
            <div class="col-lg-1"></div>
        </div>    
        
        <div class="row max-1360 grid media gx-lg-2 d-none d-md-flex">
            <div class="col-lg-1"></div>
            <?php for ($i=20; $i<count($repeater_data); $i++) { ?>
              <div class="col-md-3 col-lg-2 d-flex">
                  <div class="media-item-contents grid-item">
                  <?php 
                      $image = $repeater_data[$i]['image'];
                      $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                      ?>
                      <img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($alt_text); ?>">
                  </div>
              </div>
            <?php } ?>
            <div class="col-lg-1"></div>
        </div>    

    <?php } ?>

</div>
