<!--  EXAMPLE 1 -->
<!--
<?php
/*
Plugin Name: TF Plugin Backup
Description: A test plugin to demonstrate wordpress functionality
Author: Simon Lissack
Version: 0.1
*/
        echo "<h1>Hello world!</h1>";
 
?>
-->

<!-- EXAMPLE 2 -->

<?php
/*
Plugin Name: TF Plugin
Description: A test plugin to demonstrate wordpress functionality
Author: Simon Lissack
Version: 0.1
*/
 
add_action('admin_menu', 'test_plugin_setup_menu');
 
	function test_plugin_setup_menu(){
        add_menu_page( 'Test Plugin Page', 'TF Plugin', 'manage_options', 'tf-plugin', 'test_init' );
	}
 
	function test_init(){
        echo "<h1>Hello World3!</h1>";
        ?>
        Name: <input type="text" name="name" value="<?php echo $name;?>"> 
	        
	    <input type="submit" name="submit" id="submit2"> <!-- value="Submit"> -->
	    <input type="text" name="submit" id="submit">
	    <input type="text" id="name2" />
	    <button onclick="myFunction()">Click me</button>
		<p id="demo"></p>  
		
		 <script>
		 	function myFunction() {
		 		var name2 = document.getElementById("name2").value;
		 		var submit = document.getElementById("submit").value;
		 		document.getElementById("demo").innerHTML = "Test13 " + submit + " " + name2;
		 		
		 		/*wp-content/themes/Alclair-Total-Child/configurator/js/configurator.js
				wp-content/plugins/tf_plugin/php_file.php*/
				<?php
					$cwd = getcwd();
					$type = gettype($cwd);
					$path_to_file = '../../themes/Alclair-Total-Child/configurator/js/practice_file_tf.js';
					$absolute_path = realpath("php_file.php");
					//$path_to_file = 'wp-content/themes/Alclair-Total-Child/configurator/js/practice_file_tf.js';
//$path_to_file = '/home/alclairc/domains/staging.alclair.com/public_html/wp-content/themes/Alclair-Total-Child/configurator/js/practice_file_tf.js';
$path_to_file = '/public_html/wp-content/themes/Alclair-Total-Child/configurator/js/practice_file_tf.js';
					$file_contents = file_get_contents($path_to_file);
					$path_to_file = $cwd . '/02_test_file_TF.php';
					//$file_contents = file_get_contents('/public_html/wp-content/themes/Alclair-Total-Child/configurator/js/practice_file_tf.js');
					//$file_contents = file_get_contents(__DIR__ . '/../02_test_file.php', FALSE);
					//$file_contents = file_get_contents(__DIR__ . '.php', FALSE);
					$myfile = fopen('/home/alclairc/domains/staging.alclair.com/public_html/wp-content/plugins/tf_plugin/02_test_file.php', "r") or die("Unable to open file!");
					$file_size = filesize('/home/alclairc/domains/staging.alclair.com/public_html/wp-content/plugins/tf_plugin/02_test_file.php');
					fread($myfile, 64);
					fclose($myfile);
					$file = __DIR__ . '/../02_test_file.php';
					//$file_contents = str_replace("TYLER", "WAYNE", $file_contents);
					//file_put_contents($path_to_file, $file_contents);
				?>
				var cwd = "<?php echo $cwd ?>";
				var type = "<?php echo $type ?>"; 
				var path2file = "<?php echo $path_to_file ?>";
				var file_contents = "<?php echo $file_contents ?>";
				var file = "<?php echo $file ?>";
				var absolute_path = "<?php echo $absolute_path ?>";
				var fread = "<?php echo $myfile ?>";
				console.log("Working Dir Is  " + cwd);
				//console.log("Type Is  " + type);
				console.log("Absolute Path is  " + absolute_path);
				console.log("FRead Is  " + fread);
				console.log("Contents are  " + file_contents);
				//console.log("File is  " + file);
		 		
			}
		</script>   
		
<?php
	} 
	
?>