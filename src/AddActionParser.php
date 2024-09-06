<?php

declare( strict_types=1 );

namespace Tuncay\PsalmWpTaint\src;

use BadMethodCallException;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;

/**
 * @author Tuncay Alarcin
 */
class AddActionParser {
	protected static $instance;
	public array $foundExpressions = [];
	protected array $actionsMap = [];
	protected array $analyzedFiles = [];

	protected function __construct() {
	}

	protected function __clone() {
	}

	public function __wakeup() {
		throw new BadMethodCallException( 'Cannot unserialize a singleton.' );
	}

	/**
	 * Retrieves singleton instance of AddActionParser.
	 *
	 * @return AddActionParser
	 */
	public static function getInstance(): AddActionParser {
		if ( self::$instance === null ) {
			self::$instance = new AddActionParser();
		}

		return self::$instance;
	}

	/**
	 * Checks whether given expression is a function call of function add_action().
	 *
	 * @param Expr $expr
	 *
	 * @return bool
	 */
	public function isAddAction( Expr $expr ): bool {
		return $expr instanceof FuncCall && $expr->name == 'add_action';
	}

	public function addExpression( Expr $expr ): void {
		if ( ! in_array( $expr, $this->foundExpressions ) ) {
			$this->foundExpressions[] = $expr;
		}
	}

	/**
	 * Parses found add_action() expressions.
	 *
	 * When called the function retrieves the add_action() function calls arguments and
	 * adds the given hooks and callback function names to the actionsMap.
	 *
	 * @return void
	 */
	public function parseFoundExpressions(): void {
		foreach ( $this->foundExpressions as $expr ) {
			$args = $this->getArgs( $expr );
			if ( ! array_key_exists( $args["hook"], $this->actionsMap ) ) {
				$this->actionsMap[ $args["hook"] ] = array( $args["callback"] );
			} else {
				$this->actionsMap[ $args["hook"] ][] = $args["callback"];
			}
		}
	}

	/**
	 * Writes the actionsMap to a .json file at given filepath.
	 *
	 * @param string $filepath
	 *
	 * @return void
	 */
	public function writeActionsMapToFile( string $filepath ): void {
		file_put_contents( $filepath, json_encode( $this->actionsMap ) );
	}

	/**
	 * Tries to read a .json file at the given filepath and decodes it to the actionsMap.
	 *
	 * @param string $filepath
	 *
	 * @return void
	 */
	public function readActionsMapFromFile( string $filepath ): void {
		if ( ! file_exists( $filepath ) ) {
			return;
		}

		$file = file_get_contents( $filepath );
		if ( $file == null ) {
			return;
		}

		$this->actionsMap = json_decode( $file, true );
	}

	/**
	 * Retrieves the actionsMap.
	 *
	 * @return array
	 */
	public function getActionsMap(): array {
		return $this->actionsMap;
	}

	/**
	 * Clears the actionsMap.
	 *
	 * @return void
	 */
	public function removeActionsMap(): void {
		$this->actionsMap = [];
	}

	public function addFile( string $filepath ): void {
		$this->analyzedFiles[] = $filepath;
	}

	public function printAnalyzedFilesToFile( string $filepath ): void {
		if ( ! file_exists( $filepath ) ) {
			fopen( $filepath, 'w' );
		}
		file_put_contents( $filepath, json_encode( $this->analyzedFiles ) );
	}

	private function getArgs( Expr $expr ): array {
		$args = array( "hook" => "", "callback" => "" );

		foreach ( $expr->args as $arg ) {
			if ( $arg->value instanceof String_ && $args["hook"] == "" ) {
				$args["hook"] = $arg->value->value;
			} else if ( $arg->value instanceof String_ && $args["callback"] == "" ) {
				$args["callback"] = $arg->value->value;
			} else if ( $arg->value instanceof Array_ ) {
				$args["callback"] = $arg->value->items[1]->value->value;
			}
		}

		return $args;
	}
}
