<?php 
add_menu_page("Deye Solutions","Deye Solutions","manage_options",__FILE__,"deye_solution_plugin_home_page",plugin_dir_url( __FILE__ )."images/icon.png"); 
function deye_solution_plugin_home_page(){
include("includes/manage.php");
}
?>