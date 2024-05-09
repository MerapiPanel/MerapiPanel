<?php

namespace MerapiPanel\Module\Article;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Util;
use Monolog\Utils;
use PDO;

class Service extends __Fragment
{


    protected $module;



    public function onCreate(Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    function fetchAll(
		$columns = ["id", "title", "slug", "data", "post_date", "update_date", "users.id as author_id", "users.name as author_name"],
		$page = null,
		$limit = null,
		$search = null,
	) {
		// Validate $limit to prevent division by zero
		if ($limit <= 0) {
			// Fetch all results if $limit is zero or negative
			$limit = null;
		}

		// Construct the main SQL query
		$mainSQL = "SELECT " . implode(",", array_map(function ($item) {
			if (strpos($item, "users.") === 0) {
				return str_replace("users.", "B.", $item);
			}
			return "A.{$item}";
		}, $columns)) . " FROM articles A LEFT JOIN users B ON A.author = B.id"
			. ($search ? " WHERE A.title LIKE '%{$search}%' OR A.description LIKE '%{$search}%' OR B.name LIKE '%{$search}%' " : "")
			. " ORDER BY A.id DESC";

		// Construct the count SQL query
		$countSQL = "SELECT COUNT(*) AS total FROM articles A LEFT JOIN users B ON A.author = B.id"
			. ($search ? " WHERE A.title LIKE '%{$search}%' OR A.description LIKE '%{$search}%' OR B.name LIKE '%{$search}%' " : "");

		// Execute the count query to get total results
		$totalCount = DB::instance()->query($countSQL)->fetchColumn();

        if(!$totalCount) {
            return [
                'articles' => [],
                'totalPages' => 0,
                'totalResults' => 0
            ];
        }

		// Calculate total pages based on total results and limit per page
		$totalPages = ($limit > 0) ? ceil($totalCount / $limit) : 0;

		// Construct the main SQL query with pagination
		$offset = ($page - 1) * $limit;
		$SQL = $mainSQL . ($limit ? " LIMIT $offset, $limit" : "");

		// Execute the main query to fetch articles
		$articles = DB::instance()->query($SQL)->fetchAll(PDO::FETCH_ASSOC);

		// Return the articles, total pages, and total results
		return [
			'articles' => $articles,
			'totalPages' => $totalPages,
			'totalResults' => $totalCount
		];
	}

    
    function fetchOne($columns = ["id", "title", "data", "slug", "post_date", "update_date"], $id = null)
    {

        $SQL = "SELECT " . implode(",", array_map(function ($item) {
            if (strpos($item, "users.") === 0) {
                return str_replace("users.", "B.", $item);
            }
            return "A.{$item}";
        }, $columns)) . " FROM articles A LEFT JOIN users B ON A.author = B.id WHERE A.id = ? ORDER BY `post_date` DESC";

        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute([$id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($article) {
            if (isset($article['data'])) {
                $article['data'] = json_decode($article['data'], true);
            }
        }

        return $article;
    }


    function fetch($columns = ["id", "title", "data", "slug", "post_date", "update_date"], $where = ["id" => 1])
    {
        $SQL = "SELECT " . implode(",", array_map(function ($item) {
            if (strpos($item, "users.") === 0) {
                return str_replace("users.", "B.", $item) . " as " . preg_replace("/[^a-zA-Z0-9]+/im", "_", $item);
            }
            return "A.{$item}";
        }, $columns)) . " FROM articles A LEFT JOIN users B ON A.author = B.id WHERE ";

        $SQL .= implode(" AND ", array_map(function ($item) {
            if (strpos($item, "users.") === 0) {
                return str_replace("users.", "B.", $item);
            }
            return "A.{$item} = ?";
        }, array_keys($where)));

        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute(array_values($where));
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($article) {
            if (isset($article['data'])) {
                $article['data'] = json_decode($article['data'], true);
            }
        }
        return $article;
    }




    function insert($title, $slug, $keywords, $category, $description, $data, $status)
    {
        if (!$this->module->getRoles()->isAllowed(1)) {
            throw new \Exception('Permission denied');
        }

        $user = Box::module("Auth")->getLogedinUser();
        if (!$user || !isset($user['id'])) {
            throw new \Exception('User not found', 404);
        }
        $id = Util::uniq(12);

        $SQL = "INSERT INTO articles (id, title, slug, keywords, category, description, data, author, status) VALUES (:id, :title, :slug, :keywords, :category, :description, :data, :author, :status)";
        $stmt = DB::instance()->prepare($SQL);
        if (
            $stmt->execute([
                "id" => $id,
                "title" => $title,
                "slug" => $slug,
                "keywords" => $keywords,
                "category" => $category,
                "description" => $description,
                "data" => !is_string($data) ? json_encode($data) : $data,
                "author" => $user['id'],
                "status" => $status
            ])
        ) {
            return [
                "id" => $id,
                "title" => $title,
                "slug" => $slug,
                "keywords" => $keywords,
                "category" => $category,
                "description" => $description,
                "data" => is_string($data) ? json_decode($data, true) : $data,
                "author" => $user['id'],
                "status" => $status
            ];
        }

        throw new \Exception('Article not save', 404);
    }


    function save($id, $title, $slug, $keywords, $category, $description, $data, $status)
    {
        if (!$this->module->getRoles()->isAllowed(1)) {
            throw new \Exception('Permission denied');
        }

        if (empty($id)) {
            return $this->insert($title, $slug, $keywords, $category, $description, $data, $status);
        }

        $article = DB::table("articles")->select("id")->where("id")->equals($id)->execute()->fetch();
        if (!$article) {
            throw new \Exception('Article not found', 404);
        }

        $payload = [
            "id" => $id,
            "title" => $title,
            "slug" => $slug,
            "keywords" => $keywords,
            "category" => $category,
            "description" => $description,
            "data" => !is_string($data) ? json_encode($data) : $data,
            "status" => $status
        ];


        $SQL = "UPDATE articles SET title = :title, slug = :slug, keywords = :keywords, category = :category, description = :description, data = :data, status = :status WHERE id = :id";
        $stmt = DB::instance()->prepare($SQL);
        if (
            $stmt->execute($payload)
        ) {
            return [
                "id" => $id,
                "title" => $title,
                "slug" => $slug,
                "keywords" => $keywords,
                "category" => $category,
                "description" => $description,
                "data" => is_string($data) ? json_decode($data, true) : $data,
                "status" => $status
            ];
        }
        throw new \Exception("Article not saved");
    }




    function update($id, $title, $slug, $keywords, $category, $description, $status)
    {

        if (!$this->module->getRoles()->isAllowed(1)) {
            throw new \Exception('Permission denied');
        }

        $SQL = "UPDATE articles SET title = ?, slug = ?, keywords = ?, category = ?, description = ?, status = ?, update_date = NOW() WHERE id = ?";
        $stmt = DB::instance()->prepare($SQL);
        if ($stmt->execute([$title, $slug, $keywords, $category, $description, $status, $id])) {
            return [
                "id" => $id,
                "title" => $title,
                "slug" => $slug,
                "keywords" => $keywords,
                "category" => $category,
                "description" => $description,
                "status" => $status
            ];
        }

        throw new \Exception("Article not updated");
    }

    function delete($id)
    {

        if (!$this->module->getRoles()->isAllowed(1)) {
            throw new \Exception('Permission denied');
        }
        
        $SQL = "DELETE FROM articles WHERE id = ?";
        $stmt = DB::instance()->prepare($SQL);
        return $stmt->execute([$id]);
    }
}
