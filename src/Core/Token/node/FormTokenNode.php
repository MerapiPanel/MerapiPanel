<?php

namespace MerapiPanel\Core\Token\node;

use MerapiPanel\Database\DB;
use MerapiPanel\Database\ORDER;
use PDO;
use Throwable;
use Twig\Node\Node;

class FormTokenNode extends Node
{

    public function __construct(Node $node, int $lineno, string $tag = null)
    {
        // Initialize the node with 'body' and optionally other parts like 'attributes'
        parent::__construct(['body' => $node], [], $lineno, $tag);
    }

    public function compile(\Twig\Compiler $compiler)
    {

        // Implement compilation to PHP code here

        try {

            DB::table("token")->delete()->where("token")->like("%123%")->execute();
            DB::table("token")->insert([
                "token" => 123,
                "user_id" => 123,
                "created_at" => date("Y-m-d H:i:s")
            ])->execute();
        } catch (Throwable $e) {

            $stream = fopen(__DIR__ . "/error.log", "w+");
            if (!$stream) die('Could not open file: ' . __DIR__ . '/.log');
            fwrite($stream, $e->getMessage());
            fclose($stream);
        }

        ob_start();

        $stmt = DB::instance()->prepare("PRAGMA table_info(token)");
        $stmt->execute();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $columnName = $row['name'];
            $columnType = $row['type'];
            $columnLength = $row['pk'] ? '(Primary Key)' : '';
        
            $columns[] = $row;
        }
        print_r($columns);
        // print_r(array_keys(->fetch(PDO::FETCH_ASSOC)));

        $output = ob_get_clean();


        $stream = fopen(__DIR__ . "/output.log", "w+");
        if (!$stream) die('Could not open file: ' . __DIR__ . '/.log');
        fwrite($stream, $output);
        fclose($stream);

        $directory = __DIR__;

        $compiler
            ->addDebugInfo($this)
            ->write("\$token = uniqid('token-');\n")
            ->write("ob_start();\n")
            ->write("echo json_encode(\"" . __DIR__ . "\");\n")
            ->subcompile($this->getNode('body'))
            ->write("\$content= ob_get_clean();\n")
            ->write("\$pattern = '/<input[^>]*name=[\'\"]([^\'\"]*)[\'\"]/i';\n")
            ->write("if (preg_match_all(\$pattern, \$content, \$matches)) {
                \$names = \$matches[1];
                foreach (\$names as \$name) {
                    echo \$name . \"\\n\";
                }
            }\n")
            //->write("\$content= ob_get_clean();\n")
            ->write("echo \"<input name='token' type='hidden' value='\$token' />\n\$content\";");
    }



    protected function getAttributesAsString()
    {
        // Assuming attributes are stored as an associative array
        $attributes = $this->getAttribute('attributes');
        $result = '';

        foreach ($attributes as $name => $value) {
            // Convert each attribute to a string part. 
            // Note: You might need to adjust this logic based on how attributes are actually stored and passed.
            $result .= sprintf(' %s="%s"', $name, htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
        }

        return $result;
    }
}
