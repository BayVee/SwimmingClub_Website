<?php get_header(); ?>

<div id="content">
    <div class="entry">
        <h1 class="title"><?php _e('Error 404 - Page Not Found', 'cpotheme'); ?></h1>
        
        <div class="content">
            <p><?php _e('The requested page could not be found. It could have been deleted or changed location. Try searching for it using the search function.', 'cpotheme'); ?></p>
            
            <div id="search_form">
                <form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
                    <input type="text" name="s" id="s" /><input type="submit" id="searchsubmit" value="Buscar" />
                </form>
            </div>
        </div>	
    </div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>