<?php


use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\AddActionParser;
use PhpParser\Error;

final class AddActionParserTests extends TestCase
{
    private AddActionParser $addActionParser;
    private NodeTraverser $traverser;
    private MockNodeVisitor $visitor;


    public function testIsAddAction(): void
    {
        $code = '<?php add_action("admin_post", array("class", "callback"));';

        $ast = $this->setUpParsing($code);
        $this->traverser->addVisitor($this->visitor);
        $this->traverser->traverse($ast);

        $this->assertSame(1, sizeof($this->addActionParser->foundExpressions));
        $this->cleanUp();
    }

    public function testParse(): void
    {
        $code = '<?php add_action("admin_post", array("class", "callback"));';
        $expected = array("admin_post" => array("callback"));

        $ast = $this->setUpParsing($code);
        $this->traverser->addVisitor($this->visitor);
        $this->traverser->traverse($ast);

        $this->addActionParser->parseFoundExpressions();

        $this->assertSame($expected, $this->addActionParser->getActionsMap());
        $this->cleanUp();
    }

    public function testParsingFile(): void
    {
        $testFilePath = "./res/psalm/test-file.php";
        $expectedSizeOfFoundExpressions = 3;
        $expectedActionsMap = array("admin_post" => array("example_admin_post_callback", "example_admin_post_callback"), "test_hook" => array("example_admin_post_callback"));

        $ast = $this->setUpParsing(file_get_contents($testFilePath));
        $this->traverser->addVisitor($this->visitor);
        $this->traverser->traverse($ast);

        $this->assertSame($expectedSizeOfFoundExpressions, sizeof($this->addActionParser->foundExpressions));

        $this->addActionParser->parseFoundExpressions();
        $this->assertSame($expectedActionsMap, $this->addActionParser->getActionsMap());

        $this->cleanUp();
    }


    public function testWriteToFile(): void
    {
        $testFilePath = "./res/psalm/test-file.php";
        $filePathToWrite = "./res/actions-map.json";
        $expectedActionsMap = array("admin_post" => array("example_admin_post_callback", "example_admin_post_callback"), "test_hook" => array("example_admin_post_callback"));

        $ast = $this->setUpParsing(file_get_contents($testFilePath));
        $this->traverser->addVisitor($this->visitor);
        $this->traverser->traverse($ast);

        $this->addActionParser->parseFoundExpressions();
        $this->addActionParser->writeActionsMapToFile($filePathToWrite);

        $this->addActionParser->readActionsMapFromFile($filePathToWrite);
        $this->assertSame($expectedActionsMap, $this->addActionParser->getActionsMap());
        $this->cleanUp();
    }

    public function testActionsMapSetWithEmptyActionsMapFile(): void
    {
        $testFilePath = "./res/actions-map-empty.json";
        $expected = [];

        $this->addActionParser->readActionsMapFromFile($testFilePath);

        $this->assertSame($expected, $this->addActionParser->getActionsMap());

        $this->cleanUp();
    }

    public function testParsingMultipleFiles(): void
    {
        $testFilePaths = ["./res/psalm/test-file.php", "./res/psalm/test-file-2.php"];
        $mapFilePath = "./res/actions-map-multiple.json";
        $expectedActionsMap = array("admin_post" => array("example_admin_post_callback", "example_admin_post_callback"),
            "test_hook" => array("example_admin_post_callback"),
            "admin_menu" => array("example_admin_menu_callback"),
            "wp_ajax" => array("example_wp_ajax_callback")
        );
        $expectedSizeOfFoundExpressions = 5;

        foreach ($testFilePaths as $testFilePath) {
            $ast = $this->setUpParsing(file_get_contents($testFilePath));
            $this->traverser->addVisitor($this->visitor);
            $this->traverser->traverse($ast);
        }
        $this->assertSame($expectedSizeOfFoundExpressions, sizeof($this->addActionParser->foundExpressions));

        $this->addActionParser->parseFoundExpressions();
        $this->assertSame($expectedActionsMap, $this->addActionParser->getActionsMap());
        $this->cleanUp();
    }

    protected function setUp(): void
    {
        $this->addActionParser = AddActionParser::getInstance();
        $this->traverser = new NodeTraverser();
        $this->visitor = new MockNodeVisitor();
    }

    protected function cleanUp(): void
    {
        $this->addActionParser->removeActionsMap();
        $this->addActionParser->foundExpressions = [];
        $files = ["./res/actions-map.json", "./res/actions-map-multiple.json"];

        foreach ($files as $file) {
            if (file_exists($file)) {
                file_put_contents($file, "");
            }
        }
    }

    protected function setUpParsing(string $code): array
    {
        $parser = (new ParserFactory())->createForNewestSupportedVersion();

        try {
            return $parser->parse($code);
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return [];
        }

    }

}

class MockNodeVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node): void
    {
        if ($node instanceof Node\Expr && AddActionParser::getInstance()->isAddAction($node)) {
            AddActionParser::getInstance()->addExpression($node);
        }
    }
}