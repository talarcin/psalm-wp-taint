<?php

namespace Tuncay\PsalmWpTaint\src;

class PsalmOutputParser
{
    public function __construct()
    {
    }

    public function parsePsalmOutput(array $output): array|bool
    {
        if ($this->hasNoErrors($output)) return false;

        $cleanedOutput = $this->cleanOutput($output);
        $errors = $this->splitPsalmOutputIntoErrorMessages($cleanedOutput);
        $psalmErrors = [];

        foreach ($errors as $error) {
            $psalmError = $this->parseErrorMessage($error);
            $psalmErrors[] = $psalmError;
        }

        return array("count" => count($psalmErrors), "errors" => $psalmErrors);
    }

    protected function splitPsalmOutputIntoErrorMessages(array $output): array
    {
        $errors = [];
        $currentError = [];
        $errorStarted = false;
        $errorEnded = false;

        foreach ($output as $index => $line) {

            if (str_starts_with($line, "ERROR")) {
                $errorStarted = true;
                $errorEnded = false;
            }
            if (strlen($line) == 0 && strlen(trim($output[$index + 1])) == 0 && strlen(trim($output[$index + 2])) == 0) {
                $errorEnded = true;
                $errorStarted = false;
                $errors[] = $currentError;
                $currentError = [];
            }
            if ($errorStarted && !$errorEnded) {
                $currentError[] = $line;
            }
        }

        return $errors;
    }

    private function hasNoErrors(array $output): bool
    {
        return count($output) <= 8;
    }

    private function parseErrorMessage(array $error): PsalmError
    {
        $psalmError = new PsalmError();

        print_r($error[0]);
        print_r($error[1]);

        $errorType = explode(":", $error[0])[1];
        $errorPath = explode(" ", $error[1])[1];


        $psalmError->errorType = trim($errorType);
        $psalmError->errorPath = trim($errorPath);

        $messageItem = array("id" => "", "stmt" => "");

        for ($i = 3; $i < count($error); $i++) {

            if (strlen(trim($error[$i])) == 0) {
                continue;
            } else if (array_key_exists($i, $error) && array_key_exists($i + 1, $error)) {
                $messageItem["id"] = $error[$i];
                $messageItem["stmt"] = $error[$i + 1];
                $psalmError->errorMessage[] = $messageItem;
                $i = $i + 2;
            }
        }
        return $psalmError;
    }

    private function cleanOutput(array $output): array
    {
        $cleanedOutput = [];

        foreach ($output as $line) {
            $line = trim($line);
            $line = Util::removeAnsiCodes($line);
            $cleanedOutput[] = $line;
        }

        return $cleanedOutput;
    }
}
