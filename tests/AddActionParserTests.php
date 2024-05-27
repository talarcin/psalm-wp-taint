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

    }

    public function testParsingFile(): void
    {
        $testFilePath = "./res/test-file.php";
        $expectedSizeOfFoundExpressions = 3;
        $expectedActionsMap = array("admin_post" => array("example_admin_post_callback", "example_admin_post_callback"), "test_hook" => array("example_admin_post_callback"));

        $ast = $this->setUpParsing(file_get_contents($testFilePath));
        $this->traverser->addVisitor($this->visitor);
        $this->traverser->traverse($ast);

        $this->addActionParser->parseFoundExpressions();

        $this->assertSame($expectedSizeOfFoundExpressions, sizeof($this->addActionParser->foundExpressions));
        $this->assertSame($expectedActionsMap, $this->addActionParser->getActionsMap());
    }

    public function testWriteToFile(): void
    {
        $testFilePath = "./res/test-file.php";
        $filePathToWrite = "./res/actions-map.json";
        $expectedActionsMap = array("admin_post" => array("example_admin_post_callback", "example_admin_post_callback"), "test_hook" => array("example_admin_post_callback"));

        $ast = $this->setUpParsing(file_get_contents($testFilePath));
        $this->traverser->addVisitor($this->visitor);
        $this->traverser->traverse($ast);

        $this->addActionParser->parseFoundExpressions();
        $this->addActionParser->writeActionsMapToFile($filePathToWrite);

        $this->addActionParser->readActionsMapFromFile($filePathToWrite);
        // TODO remove file afterwards for clean up
        $this->assertSame($expectedActionsMap, $this->addActionParser->getActionsMap());
    }

    public function testActionsMapSetWithEmptyActionsMapFile(): void
    {
        $testFilePath = "./res/actions-map-empty.json";
        $expected = [];

        $this->addActionParser->readActionsMapFromFile($testFilePath);

        $this->assertSame($expected, $this->addActionParser->getActionsMap());

    }

    protected function setUp(): void
    {
        $this->addActionParser = new AddActionParser();
        $this->traverser = new NodeTraverser();
        $this->visitor = new MockNodeVisitor($this->addActionParser);
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
    private AddActionParser $addActionParser;

    public function __construct(AddActionParser $addActionParser)
    {
        $this->addActionParser = $addActionParser;
    }

    public function enterNode(Node $node): void
    {
        if ($node instanceof Node\Expr) {
            if ($this->addActionParser->isAddAction($node)) {
                $this->addActionParser->foundExpressions[] = $node;
            }
        }
    }
}