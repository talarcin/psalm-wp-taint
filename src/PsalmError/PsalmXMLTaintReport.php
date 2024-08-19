<?php

namespace Tuncay\PsalmWpTaint\src\PsalmError;

class PsalmXMLTaintReport {
	private array $report;

	public function __construct( string $xmlReportFilePath ) {
		$this->report = [];
		$this->parseXMLReport( $xmlReportFilePath );
	}

	public function reportValues(): array {
		return $this->report;
	}

	private function parseXMLReport( string $xmlReportFilePath ): void {
		if ( ! str_ends_with( $xmlReportFilePath, ".xml" ) || ! file_exists( $xmlReportFilePath ) ) {
			print_r( "Given filepath doesn't contain a valid XML file.\n" );
		}

		$simpleXMlObject = simplexml_load_file( $xmlReportFilePath );

		$count = count( $simpleXMlObject->item );

		$reportArray = [ "count" => $count, "errors" => [] ];
		foreach ( $simpleXMlObject->item as $item ) {
			$itemReport = [
				"errorType"    => $item->type->__toString(),
				"errorPath"    => $item->file_path->__toString(),
				"errorMessage" => []
			];

			foreach ( $item->taint_trace as $trace ) {
				$id   = $trace->file_path ?
					"" . $trace->label . " - " . $trace->file_path . ":" . $trace->line_from . ":" . $trace->column_from
					: $trace->label->__toString();
				$stmt = $trace->snippet ? $trace->snippet->__toString() : "<no known location>";

				$itemReport["errorMessage"][] = [ "id" => $id, "stmt" => $stmt ];
			}
			$reportArray["errors"][] = $itemReport;
		}

		$this->report = $reportArray;

	}
}