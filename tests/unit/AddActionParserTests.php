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

    private string $test_file_path = "./res/test-file.php";
    private Parser $parser;

    public function testIsAddAction(): void
    {
        $add_action_parser = new AddActionParser();
        $code = '<?php add_action("admin_post", array("class", "callback"));';

        $ast = $this->setUpParsing($code);

        $traverser = new NodeTraverser();
        $visitor = new MockNodeVisitor($add_action_parser);
        $traverser->addVisitor($visitor);
        $traverser->traverse($ast);

        $this->assertSame(1, sizeof($add_action_parser->found_expr));
    }

    public function testParse(): void
    {
        $add_action_parser = new AddActionParser();
        $code = '<?php add_action("admin_post", array("class", "callback"));';
        $expected = array("admin_post" => array("callback"));

        $ast = $this->setUpParsing($code);

        $traverser = new NodeTraverser();
        $visitor = new MockNodeVisitor($add_action_parser);
        $traverser->addVisitor($visitor);
        $traverser->traverse($ast);

        $add_action_parser->parse();
        $this->assertSame($expected, $add_action_parser->actions_map);

    }

    protected function setUpParsing(string $code): array
    {
        $this->parser = (new ParserFactory())->createForNewestSupportedVersion();

        try {
            return $this->parser->parse($code);
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return [];
        }

    }
}

class MockNodeVisitor extends NodeVisitorAbstract
{
    private AddActionParser $add_action_parser;

    public function __construct(AddActionParser $add_action_parser)
    {
        $this->add_action_parser = $add_action_parser;
    }

    public function enterNode(Node $node): void
    {
        if ($node instanceof Node\Expr) {
            if ($this->add_action_parser->isAddAction($node)) {
                $this->add_action_parser->found_expr[] = $node;
            }
        }
    }
}