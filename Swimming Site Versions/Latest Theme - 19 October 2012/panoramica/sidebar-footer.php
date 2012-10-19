<?php if(!is_active_sidebar('first-footer-widget-area')
&& !is_active_sidebar('second-footer-widget-area')
&& !is_active_sidebar('third-footer-widget-area')
&& !is_active_sidebar('fourth-footer-widget-area')) return; ?>

<?php if(is_active_sidebar('first-footer-widget-area')): ?>
    <div class="footerwidget">
        <ul class="widget">
            <?php dynamic_sidebar('first-footer-widget-area'); ?>
        </ul>
    </div>
<?php endif; ?>

<?php if(is_active_sidebar('second-footer-widget-area')): ?>
    <div class="footerwidget">
        <ul class="widget">
            <?php dynamic_sidebar('second-footer-widget-area'); ?>
        </ul>
    </div>
<?php endif; ?>

<?php if(is_active_sidebar('third-footer-widget-area')): ?>
    <div class="footerwidget">
        <ul class="widget">
            <?php dynamic_sidebar('third-footer-widget-area'); ?>
        </ul>
    </div>
<?php endif; ?>

<?php if(is_active_sidebar('fourth-footer-widget-area')): ?>
    <div class="footerwidget_last">
        <ul class="widget">
            <?php dynamic_sidebar('fourth-footer-widget-area'); ?>
        </ul>
    </div>
<?php endif; ?>