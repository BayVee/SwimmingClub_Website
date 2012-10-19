<div id="sidebar" <?php if(cpotheme_get_option('cpo_sidebar_position') == 'left') echo 'class="left"'; ?>>
    <ul class="widget">
        <?php if(!dynamic_sidebar('home-widget-area')): ?>
        <li id="search" class="widget_container widget_search">
            <?php get_search_form(); ?>
        </li>
    
        <li id="archives" class="widget_container">
            <h3 class="widget-title"><?php _e('Archives', 'cpotheme'); ?></h3>
            <ul>
                <?php wp_get_archives('type=monthly'); ?>
            </ul>
        </li>
    
        <li id="meta" class="widget_container">
            <h3 class="widget-title"><?php _e('Meta', 'cpotheme'); ?></h3>
            <ul>
                <?php wp_register(); ?>
                <li><?php wp_loginout(); ?></li>
                <?php wp_meta(); ?>
            </ul>
        </li>
        <?php endif; ?>
    </ul>
</div>