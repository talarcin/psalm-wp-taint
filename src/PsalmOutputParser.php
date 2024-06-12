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

        $errors = $this->splitPsalmOutputIntoErrorMessages($output);
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
            $line = trim($line);

            if (str_starts_with($line, 'ERROR:')) {
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

        $psalmError->errorType = trim(explode(':', $error[0])[1]);
        $psalmError->errorPath = trim(explode(" ", $error[1])[1]);

        $messageItem = array("id" => "", "stmt" => "");

        for ($i = 3; $i < count($error); $i++) {

            if (strlen(trim($error[$i])) == 0) {
                continue;
            } else {
                $messageItem["id"] = $error[$i];
                $messageItem["stmt"] = $error[$i + 1];
                $psalmError->errorMessage[] = $messageItem;
                $i = $i + 2;
            }
        }

        return $psalmError;
    }
}
