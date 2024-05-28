<?php

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\FunctionBodyGetter;

class FunctionBodyGetterTests extends TestCase
{
    public function testGettingMatchingFunctionsBodies()
    {
        $actionsMap = array("admin_post" => array("testFunctionOne", "testFunctionTwo"));
        $filepaths = array("./res/test-functions.php");
        $functionBodyGetter = $this->makeNewFunctionBodyGetter($actionsMap);

        foreach ($filepaths as $filepath) {
            $functionBodyGetter->filterMatchingFunctionBodiesFromFile($filepath);
        }

        $this->assertSame(2, sizeof($functionBodyGetter->getFunctionNames()));
        $this->assertSame(2, sizeof($functionBodyGetter->getFunctionStmts()));

        $functionBodyGetter->writeFunctionStmtsToFile("./res/test-functions-result.php");

        $expectedCode = file_get_contents("./res/test-functions.php");
        $expectedCode = preg_replace('/\s+/', '', $expectedCode);
        $actualCode = preg_replace('/\s+/', '', file_get_contents("./res/test-functions-result.php"));
        
        $this->assertSame($expectedCode, $actualCode);
    }

    private function makeNewFunctionBodyGetter(array $actionsMap): FunctionBodyGetter
    {
        return new FunctionBodyGetter($actionsMap);
    }

    protected function setUp(): void
    {
        file_put_contents("./res/test-functions-result.php", "<?php");
    }
}