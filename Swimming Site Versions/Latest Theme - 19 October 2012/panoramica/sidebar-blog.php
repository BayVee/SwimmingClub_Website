<div id="sidebar" <?php if(cpotheme_get_option('cpo_sidebar_position') == 'left') echo 'class="left"'; ?>>
    <ul class="widget">
        <?php if(!dynamic_sidebar('blog-widget-area')): ?>
        <li id="search" class="widget_container widget_search">
            <?php get_search_form(); ?>
        </li>    
        <?php endif; ?>
    </ul>
</div>