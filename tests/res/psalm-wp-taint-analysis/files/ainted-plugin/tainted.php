<?php

add_action( "admin_post_tainted", "testFunction" );
add_action( "admin_post_tainted_two", "testFunctionTwo" );

function testFunction(): void {
	echo $_POST["tainted"];
}

function testFunctionTwo(): void {
	print_r( $_POST["taintedTwo"] );
}

testFunction();
testFunctionTwo();