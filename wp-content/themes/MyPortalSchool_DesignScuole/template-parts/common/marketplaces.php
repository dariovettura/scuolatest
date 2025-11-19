<?php
    $download_options = get_fields('options');

    // $app_store_id = $download_options['apple_app_store_id'];
    // $android_app_package_name = $download_options['android_app_package_name'];
    $app_store_id = get_field('apple_app_store_id', 'option');
    $android_app_package_name = get_field('android_app_package_name', 'option');

    // $app_gallery_id = $download_options['app_gallery_id'];
    if(!!$app_store_id || !!$android_app_package_name) {
?>
    <div class="py-4 home-message red marketplaces">
        <div class="container">
            <div class="row">
                <p class="col-lg-6 col-md-12 msg text-center mb-sm-3 mb-md-3 mb-lg-0">Scarica la nostra app ufficiale su:</p>
                <div class="col-lg-6 col-md-12 text-center">
                    <?php if(!!$app_store_id) { ?>
                        <a href="https://apps.apple.com/it/app/myp/id<?= $app_store_id; ?>" target="_blank" class="marketplace btn btn-sm btn-outline-white ml-2 mr-2" role="link"><img src="<?= get_stylesheet_directory_uri() . '/assets/icons/icon-app-store.svg'; ?>" alt="App Store"> App Store</a>
                    <?php } ?>
                    <?php if(!!$android_app_package_name) { ?>
                        <a href="https://play.google.com/store/apps/details?id=<?= $android_app_package_name; ?>" target="_blank" class="marketplace btn btn-sm btn-outline-white ml-2 mr-2" role="link"><img src="<?= get_stylesheet_directory_uri() . '/assets/icons/icon-play-store.svg'; ?>" alt="Play Store"> Play Store</a>
                    <?php } ?>
                    <?php
                        /*
                        if(!!$huawei_app_package_name) { ?>
                            <a href="#" target="_blank" class="marketplace btn btn-sm btn-outline-white ml-2 mr-2" role="link"><img src="<?= get_stylesheet_directory_uri() . '/assets/icons/icon-app-gallery.svg'; ?>"> App Gallery</a>
                        <?php }
                        */
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>