<?php


namespace Tuncay\PsalmWpTaint\tests\unit;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\AddActionParser;

final class AddActionParserTest extends TestCase
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
        $expected = array("admin_post" => ["callback"]);

        $ast = $this->setUpParsing($code);
        $this->traverser->addVisitor($this->visitor);
        $this->traverser->traverse($ast);

        $this->addActionParser->parseFoundExpressions();

        $this->assertSame($expected, $this->addActionParser->getActionsMap());
        $this->cleanUp();
    }

    public function testParsingFile(): void
    {
        $testFilePath = "./tests/res/psalm/test-file.php";
        $expectedSizeOfFoundExpressions = 3;
        $expectedActionsMap = array(
            "admin_post" => ["example_admin_post_callback", "example_admin_post_callback"],
            "test_hook" => ["example_admin_post_callback"]
        );

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
        $testFilePath = "./tests/res/psalm/test-file.php";
        $filePathToWrite = "./tests/res/actions-map.json";
        $expectedActionsMap = array(
            "admin_post" => ["example_admin_post_callback", "example_admin_post_callback"],
            "test_hook" => ["example_admin_post_callback"]
        );

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
        $testFilePath = "./tests/res/actions-map-empty.json";
        $expected = [];

        $this->addActionParser->readActionsMapFromFile($testFilePath);

        $this->assertSame($expected, $this->addActionParser->getActionsMap());

        $this->cleanUp();
    }

    public function testParsingMultipleFiles(): void
    {
        $testFilePaths = ["./tests/res/psalm/test-file.php", "./tests/res/psalm/test-file-2.php"];
        $mapFilePath = "./tests/res/actions-map-multiple.json";
        $expectedActionsMap = array(
            "admin_post" => ["example_admin_post_callback", "example_admin_post_callback"],
            "test_hook" => ["example_admin_post_callback"],
            "admin_menu" => ["example_admin_menu_callback"],
            "wp_ajax" => ["example_wp_ajax_callback"],
            "init" => ["adrotate_insert_group"]
        );
        $expectedSizeOfFoundExpressions = 6;

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

    public function testParsingFileThree(): void
    {
        $testFilePath = "./tests/res/psalm/test-file-3.php";
        $expectedActionsMap = array(
            'admin_head' => ['disable_all_in_one_free'],
            'plugins_loaded' => ['aiosp_add_cap', "aioseop_init_class", 'version_updates'],
            'admin_notices' => ["admin_notices_already_defined"],
            'admin_init' => ['version_updates', 'aioseop_welcome', 'aioseop_scan_post_header'],
            'init' => ['add_hooks', 'aioseop_load_modules'],
            'shutdown' => ['aioseop_ajax_scan_header'],
            'wp_ajax_aioseop_ajax_save_meta' => ['aioseop_ajax_save_meta'],
            'wp_ajax_aioseop_ajax_save_url' => ['aioseop_ajax_save_url'],
            'wp_ajax_aioseop_ajax_delete_url' => ['aioseop_ajax_delete_url'],
            'wp_ajax_aioseop_ajax_scan_header' => ['aioseop_ajax_scan_header'],
            'wp_ajax_aioseop_ajax_facebook_debug' => ['aioseop_ajax_facebook_debug'],
            'wp_ajax_aioseop_ajax_save_settings' => ['aioseop_ajax_save_settings'],
            'wp_ajax_aioseop_ajax_get_menu_links' => ['aioseop_ajax_get_menu_links'],
            'wp_ajax_aioseo_dismiss_yst_notice' => ['aioseop_update_yst_detected_notice'],
            'wp_ajax_aioseo_dismiss_visibility_notice' => ['aioseop_update_user_visibilitynotice'],
            'wp_ajax_aioseo_dismiss_woo_upgrade_notice' => ['aioseop_woo_upgrade_notice_dismissed'],
            'admin_enqueue_scripts' => ['aioseop_admin_enqueue_styles']
        );
        $expectedSizeOfFoundExpressions = 22;
        $ast = $this->setUpParsing(file_get_contents($testFilePath));
        $this->traverser->addVisitor($this->visitor);
        $this->traverser->traverse($ast);

        $this->assertSame($expectedSizeOfFoundExpressions, sizeof($this->addActionParser->foundExpressions));

        $this->addActionParser->parseFoundExpressions();

        $this->assertSame($expectedActionsMap, $this->addActionParser->getActionsMap());
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
        $files = ["./tests/res/actions-map.json", "./tests/res/actions-map-multiple.json"];

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
