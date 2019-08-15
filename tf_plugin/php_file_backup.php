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
Plugin Name: TF Plugin 1
Description: A test plugin to demonstrate wordpress functionality
Author: Simon Lissack
Version: 0.1
*/
add_action('admin_menu', 'test_plugin_setup_menu');
 
function test_plugin_setup_menu(){
        add_menu_page( 'TF Plugin Page', 'TF Plugin', 'manage_options', 'tf-plugin', 'test_init' );
}
 
/*
function test_init(){
        echo "<h1>Hello World!</h1>";
        ?>
        <html>
			<body>
				<form action="welcome.php" method="post">
					Name: <input type="text" name="name"><br>
					E-mail: <input type="text" name="email"><br>
					<i	nput type="submit">
				</form>
			</body>
		</html>
		*/
<?php
}
 
?>

