<?php
/*
Template Name: Contact Form
*/
?>

<?php //If the form is submitted
$email_sent = false;
if(isset($_POST['contact_submit'])){
    $error = false;

    //Check if hidden field has been filled out
    if(trim($_POST['contact_message']) != ''){
            $error_captcha = true;
    }else{

        //Check to make sure that the name field is not empty
        if(trim($_POST['contact_name']) == ''){
                $error_name =  __('This field cannot be left empty.', 'cpotheme'); 
                $error = true;
        }else{
                $name = trim($_POST['contact_name']);
        }

        //Check to make sure sure that a valid email address is submitted
        if(trim($_POST['contact_email']) == ''){
                $error_email = __('This field cannot be left empty.', 'cpotheme');
                $error = true;
        }else if(!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['contact_email']))){
                $error_email = __('The email address has an incorrect format..', 'cpotheme');
                $error = true;
        }else{
                $email = trim($_POST['contact_email']);
        }

        //Check to make sure comments were entered	
        if(trim($_POST['contact_comments']) == ''){
                $error_comments = __('This field cannot be left empty.', 'cpotheme');
                $error = true;
        }else{
                if(function_exists('stripslashes')){
                        $comments = stripslashes(trim($_POST['contact_comments']));
                } else {
                        $comments = trim($_POST['contact_comments']);
                }
        }

        //All checks passed, send email
        if(!$error){
            $emailTo = cpotheme_get_option('cpo_contact_email'); 
            $subject = bloginfo('name').' - '.__('New contact form response by', 'cpotheme').' '.$name;
            $body = '';
            $body .= __('Name', 'cpotheme').': '.$name." \n\n";
            $body .= __('Email', 'cpotheme').': '.$email." \n\n";
            $body .= __('Message', 'cpotheme').': '.$comments." \n\n";
            $admin_body = '';
            $admin_body .= __('IP Address', 'cpotheme').': '.$_SERVER['REMOTE_ADDR']." \n\n";
            $admin_body .= __('Time', 'cpotheme').': '.date('d/m/Y H:i')." \n\n";
            $headers = 'From: '.' <'.$email.'>'."\r\n".'Reply-To: '.$email;
            wp_mail($emailTo, $subject, $body.$admin_body, $headers);

            //Send a copy to sender
            $subject = bloginfo('name').' - '.__('Your message has been sent', 'cpotheme').' '.get_bloginfo('title');
            $headers = 'From: '.' <'.$emailTo.'>'."\r\n".'Reply-To: '.$emailTo;
            wp_mail($email, $subject, $body, $headers);
            $email_sent = true;
        }
    }
} ?>

<?php get_header(); ?>

<div id="content">
    <?php if(have_posts()) while(have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" class="entry">
        <h1 class="title"><?php the_title(); ?></h1>

        <div class="content">
            <?php the_content(); ?>
            <?php wp_link_pages(array('before' => '<div class="page-link">'.__('Pages:', 'cpotheme'), 'after' => '</div>')); ?>
        </div>
        
        <?php if(cpotheme_get_option('cpo_contact_email') == ''){ ?>
        <div class="scode_box scode_box_warn"><?php _e('The email address for the contact form is not configured.', 'cpotheme');  ?></div>
        <?php }else{ ?>
        <form action="<?php the_permalink(); ?>" id="contact" method="post">
            <div id="contact_form">
                <?php if(isset($email_sent) && $email_sent == true){ ?>
                <div class="box box_ok"><?php _e('Your message has been sent.', 'cpotheme');  ?></div>
                <?php } ?>

                <div class="field">
                    <label for="contactName"><?php _e('Name', 'cpotheme'); ?></label>
                    <input type="text" name="contact_name" id="contact_name" value="<?php if(isset($_POST['contact_name'])) echo $_POST['contact_name'];?>" class="txt requiredField" />
                    <?php if(isset($error_name) && $error_name != ''){ ?>
                        <span class="error"><?php echo $error_name;?></span> 
                    <?php } ?>
                </div>

                <div class="field">
                    <label for="email"><?php _e('Email', 'cpotheme'); ?></label>
                    <input type="text" name="contact_email" id="contact_email" value="<?php if(isset($_POST['contact_email']))  echo $_POST['contact_email'];?>" class="txt requiredField email" />
                    <?php if(isset($error_email) && $error_email != ''){ ?>
                        <span class="error"><?php echo $error_email;?></span>
                    <?php } ?>
                </div>

                <div class="field">
                    <label for="commentsText"><?php _e('Message', 'cpotheme'); ?></label>
                    <textarea name="contact_comments" id="contact_comments" rows="20" cols="30" class="requiredField"><?php if(isset($_POST['contact_comments'])){ if(function_exists('stripslashes')){ echo stripslashes($_POST['contact_comments']); } else { echo $_POST['contact_comments']; } } ?></textarea>
                    <?php if(isset($error_comments) && $error_comments != ''){ ?>
                        <span class="error"><?php echo $error_comments;?></span> 
                    <?php } ?>
                </div>

                <div class="test">
                    <label for="contact_message" class="contact_message"><?php _e('In order to complete this form, leave this field empty', 'cpotheme') ?></label>
                    <input type="text" name="contact_message" id="contact_message" class="contact_message" value="<?php if(isset($_POST['checking']))  echo $_POST['checking'];?>" />
                </div>

                <div class="field">
                    <input type="hidden" name="contact_submit" id="contact_submit" value="true" />
                    <input class="button" type="submit" value="<?php _e('Send', 'cpotheme'); ?>" />
                </div>
            </div>
        </form>
        <?php } ?>
    </div>
    <?php endwhile; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>