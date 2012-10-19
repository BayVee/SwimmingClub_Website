<?php /*

//Shows the update form
function cpotheme_update(){
    cpotheme_update_form();
}

//Create update form
function cpotheme_update_form() {
    
    $update_values = cpotheme_update_check();
    if($update_values['filesystem'] != false){ ?>   
        <div class="wrap">
            <div class="icon32 icon_settings"></div>
            <h2><?php echo get_admin_page_title(); ?></h2>
            <?php if(isset($_GET['ok'])): ?>
            <div id="message" class="updated">
                <p><strong><?php _e('Changes have been saved.', 'cpotheme'); ?></strong></p>
            </div>
            <?php endif; ?>
            <?php if(isset($_GET['error'])): ?>
            <div id="message" class="error">
                <p><strong><?php _e('Changes could not be saved.', 'cpotheme'); ?></strong></p>
            </div>
            <?php endif; ?>
            
            <span style="display:none"><?php echo $method; ?></span>
            <form method="post"  enctype="multipart/form-data" id="cpoform" action="<?php echo $update_values['url']; ?>">
                <?php if($update_values['update_available'] == true): ?>
                <?php wp_nonce_field('update-options'); ?>
                <h2><?php _e('A new version of the CPO Core is available.', 'cpotheme'); ?></h2>
                <p><?php _e('Updating the CPO Core will download and install the latest version into your theme installation.', 'cpotheme'); ?></p>
                <p><?php _e('It is advised that you first create a backup of your theme files before executing the update process.', 'cpotheme'); ?></p>
                <h4><?php _e('Current version is', 'cpotheme'); ?> <strong><?php echo $update_values['version_current']; ?></strong></h4>	
                <h4><?php _e('Latest version is', 'cpotheme'); ?> <strong><?php echo $update_values['version_latest']; ?></strong></h4>
                <input type="submit" class="button-primary" value="<?php _e('Update Core', 'cpotheme'); ?>" />
                
                <?php else: ?>
                
                <h3><?php _e('You have the latest version of CPO Core', 'cpotheme'); ?></h3>
                <p>Current version is <strong><?php echo $update_values['version_current']; ?></strong></p>
                
				<?php endif; ?>
                <input type="hidden" name="cpo_update_save" value="save" />
                <input type="hidden" name="cpo_ftp_cred" value="<?php echo esc_attr(base64_encode(serialize($_POST))); ?>" />
            </form>           
        </div>        
    <?php  
    }   
}

//Check if any update is available
add_action('admin_head', 'cpotheme_update_check');
function cpotheme_update_check() {
    
    $method = get_filesystem_method();
	$to = ABSPATH.'wp-content/themes/'.get_option('template').'/core/';
	
	if(isset($_POST['password'])){
		$cred = $_POST;
		$filesystem = WP_Filesystem($cred);
	} elseif(isset($_POST['cpo_ftp_cred'])){
		 $cred = unserialize(base64_decode($_POST['cpo_ftp_cred']));
		 $filesystem = WP_Filesystem($cred);
	} else{
	   $filesystem = WP_Filesystem();
	}
	
	$url = admin_url('admin.php?page=cpotheme_update'); 
	
    $version_current = null;
    $version_latest = null;
    $update_available = false;
    
    if($filesystem == false){
    	request_filesystem_credentials($url);
    } else {
		$version_current = cpotheme_get_option('cpotheme_general_version');
		$version_latest = cpotheme_update_checkversion();
		
		// Test if a new version is available
		$update_available = false;
        if (strlen($version_latest) == 5) { //Only if we have the format x.x.x
        
            $loc = explode('.', $version_current);
            $rem = explode('.', $version_latest);       

            if($loc[0] < $rem[0])
                $update_available = true;
            elseif($loc[1] < $rem[1])
                $update_available = true;
            elseif($loc[2] < $rem[2])
                $update_available = true;

            if ($update_available) {
                add_action('admin_notices', 'cpotheme_update_print_advice');
            }
        }
    }
    
    return array('update_available' => $update_available, 'url' => $url, 'version_current' => $version_current, 'version_latest' => $version_latest, 'filesystem' => $filesystem);
}

function cpotheme_update_print_advice() {
    if (isset($_REQUEST['page'])) {
        $_page = strtolower(strip_tags(trim($_REQUEST['page'])));
        if ($_page != 'cpotheme_update')
            echo '<div class="updated"><p>' . __('A new version of CPO Core is available. Go to the update page.', 'cpotheme') . '</p></div>';
    } else {
        echo '<div class="updated"><p>' . __('A new version of CPO Core is available. Go to the update page.', 'cpotheme') . '</p></div>';
    }
}

//Checks for the current version of the CPO Core
function cpotheme_update_checkversion($url = '') {
    if (!empty($url)) {
        $fw_url = $url;
    } else {
        $fw_url = 'http://www.cpothemes.com/core/changelog.txt';        
    }
    $temp_file_addr = download_url($fw_url);
    if (!is_wp_error($temp_file_addr) && $file_contents = file($temp_file_addr)) {
        foreach ($file_contents as $line_num => $line) {           
            
            if (strpos($line, 'Latest Version: ') !== false) {                
                $output = trim(substr($line, 16));                
                break;
            }
        }
        unlink($temp_file_addr);
        return $output;
    } else {
        return 'Currently Unavailable';
    }
}

//Process theme update
add_action('admin_head','cpotheme_update_process');
function cpotheme_update_process(){
    
	if(isset($_REQUEST['page'])){
		$_page = strtolower(strip_tags(trim($_REQUEST['page'])));
		
		//If an update has been requested...
		if($_page == 'cpotheme_update'){
			$method = get_filesystem_method();
		
			if(isset($_POST['cpo_ftp_cred'])){
				$cred = unserialize(base64_decode($_POST['cpo_ftp_cred']));
				$filesystem = WP_Filesystem($cred);
			}else{
			   $filesystem = WP_Filesystem();
			}            
		
			if($filesystem == false && $_POST['upgrade'] != 'Proceed'){
				$method = get_filesystem_method();
				echo "<div id='filesystem-warning' class='updated fade'><p>Failed: Filesystem preventing downloads. (". $method .")</p></div>";
				return;
			}
			
			if(isset($_REQUEST['cpo_update_save'])){
		
				// Sanitize action being requested.
				$_action = strtolower(trim(strip_tags($_REQUEST['cpo_update_save'])));
		
				if($_action == 'save') {
					$file_temp = download_url('http://www.cpothemes.com/core/core.zip'); //The download URL
					if(is_wp_error($file_temp)){
						$error = $file_temp->get_error_code();
						if($error == 'http_no_url'){
							//The source file was not found or is invalid
							echo "<div id='source-warning' class='updated fade'><p>Failed: Invalid URL Provided</p></div>";
						}else{
							echo "<div id='source-warning' class='updated fade'><p>Failed: Upload - $error</p></div>";
						}
						return;
					}
					//Unzip the file contents
					global $wp_filesystem;
					$to = $wp_filesystem->wp_content_dir()."/themes/".get_option('template')."/core/";
				
					$dounzip = unzip_file($file_temp, $to);
					//Delete temporary files
					unlink($file_temp);
				
					if(is_wp_error($dounzip)){		
						$error = $dounzip->get_error_code();
						$data = $dounzip->get_error_data($error);
				
						if($error == 'incompatible_archive'){
							echo "<div id='cpo-no-archive-warning' class='updated fade'><p>Failed: Incompatible archive</p></div>";
						}
						if($error == 'empty_archive'){
							echo "<div id='cpo-empty-archive-warning' class='updated fade'><p>Failed: Empty Archive</p></div>";
						}
						if($error == 'mkdir_failed'){
							echo "<div id='cpo-mkdir-warning' class='updated fade'><p>Failed: mkdir Failure</p></div>";
						}
						if($error == 'copy_failed'){
							echo "<div id='cpo-copy-fail-warning' class='updated fade'><p>Failed: Copy Failed</p></div>";
						}
						return;
					}
					echo "<div id='framework-upgraded' class='updated fade'><p>New framework successfully downloaded, extracted and updated.</p></div>";
				}
			}
		}
	}
}

*/?>