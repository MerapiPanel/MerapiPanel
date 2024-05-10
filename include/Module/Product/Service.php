<?php
namespace MerapiPanel\Module\Product;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Util;
use PDO;
use Throwable;

class Service extends __Fragment
{
	protected Module $module;
	function onCreate(Module $module)
	{
		$this->module = $module;
	}



	public function fetch($columns = ["id", "title", "price", "category", "description", "data", "status", "post_date", "update_date"], $id = 1)
	{

		$product = DB::table("products")->select($columns)->where("id")->equals($id)->execute()->fetch(PDO::FETCH_ASSOC);
		if ($product) {
			$data = $product['data'] ?? "";
			$data = json_decode($data, true);
			$product['data'] = [
				"components" => $data["components"] ?? [],
				"css" => $data["css"] ?? "",
			];

			if (isset($data['components'])) {

				$images_wrapper = Box::module("Editor")->findComponent($data['components'], "product-images");
				$product['images'] = [];

				if (isset($images_wrapper['components']) && is_array($images_wrapper['components'])) {
					foreach ($images_wrapper['components'] as $component) {
						if ($component['type'] === "product-image" && isset($component['components'][0]['attributes']['src'])) {
							$product['images'][] = $component['components'][0]['attributes']['src'];
						}
					}
				}
			}
		}
		return $product;
	}



	function fetchAll(
		$columns = ["id", "title", "price", "category", "description", "data", "status", "post_date", "update_date", "users.id as author_id", "users.name as author_name"],
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
		}, $columns)) . " FROM products A LEFT JOIN users B ON A.author = B.id"
			. ($search ? " WHERE A.title LIKE '%{$search}%' OR A.description LIKE '%{$search}%' OR B.name LIKE '%{$search}%' " : "")
			. " ORDER BY A.id DESC";

		// Construct the count SQL query
		$countSQL = "SELECT COUNT(*) AS total FROM products A LEFT JOIN users B ON A.author = B.id"
			. ($search ? " WHERE A.title LIKE '%{$search}%' OR A.description LIKE '%{$search}%' OR B.name LIKE '%{$search}%' " : "");

		// Execute the count query to get total results
		$totalCount = DB::instance()->query($countSQL)->fetchColumn();

		if(!$totalCount) {
            return [
                'products' => [],
                'totalPages' => 0,
                'totalResults' => 0
            ];
        }

		// Calculate total pages based on total results and limit per page
		$totalPages = ($limit > 0) ? ceil($totalCount / $limit) : 0;

		// Construct the main SQL query with pagination
		$offset = ($page - 1) * $limit;
		$SQL = $mainSQL . ($limit ? " LIMIT $offset, $limit" : "");

		// Execute the main query to fetch products
		$products = DB::instance()->query($SQL)->fetchAll(PDO::FETCH_ASSOC);

		// Additional processing of products...
		if ($products) {
			foreach ($products as $key => $product) {
				if (isset($product['data']) && gettype($product['data']) == 'string') {
					$data = json_decode($product['data'], true);

					$images_wrapper = Box::module("Editor")->findComponent($data['components'], "product-images");

					$product['data'] = [
						"components" => $data["components"],
						"css" => $data["css"],
					];

					$products[$key]['images'] = [];

					foreach ($images_wrapper['components'] as $component) {
						if ($component['type'] === "product-image" && isset($component['components'][0]['attributes']['src'])) {
							$products[$key]['images'][] = $component['components'][0]['attributes']['src'];
						}
					}
				}
			}
		}

		// Return the products, total pages, and total results
		return [
			'products' => $products,
			'totalPages' => $totalPages,
			'totalResults' => $totalCount
		];
	}






	function add($data = [])
	{

		if (!$this->module->getRoles()->isAllowed(1)) {
			throw new \Exception('Permission denied');
		}

		if (gettype($data) == 'string') {
			$data = json_decode($data, true);
		}

		try {

			$cpWrapper = Box::module("Editor")->findComponent($data['components'], 'product-wrapper');
			$title = Box::module("Editor")->findComponent($cpWrapper['components'], 'product-title')['components'][0]['content'] ?? '';
			$price = Box::module("Editor")->findComponent($cpWrapper['components'], 'product-price')['components'][0]['content'] ?? '';
			$category = Box::module("Editor")->findComponent($cpWrapper['components'], 'product-category')['components'][0]['content'] ?? '';
			$description = Box::module("Editor")->findComponent($cpWrapper['components'], 'product-description')['components'][0]['content'] ?? '';
			$id = Util::uniq(12);

			$user = Box::module("Auth")->getLogedinUser();

			if (!$user || !isset($user['id'])) {
				throw new \Exception("Not allowed !", 401);
			}


			if (strlen($title) < 5) {
				throw new \Exception('Missing required parameter: title, minimum 5 characters');
			}

			if (strlen($price) < 1 || !is_numeric(preg_replace("/[^0-9]/", "", $price))) {
				throw new \Exception('Missing required parameter: price, minimum 1 number');
			}

			if (strlen($category) < 3) {
				throw new \Exception('Missing required parameter: category, minimum 3 characters');
			}

			if (strlen($description) < 30) {
				throw new \Exception('Missing required parameter: description, minimum 30 characters');
			}


			$inserted = DB::table("products")
				->insert([
					"id" => $id,
					"title" => $title,
					"price" => $price,
					"category" => $category,
					"description" => $description,
					"data" => gettype($data) === "string" ? $data : json_encode($data),
					"author" => $user['id']
				])
				->execute();

			if ($inserted) {
				return [
					"id" => $id,
					"title" => $title,
					"price" => $price,
					"category" => $category,
					"description" => $description,
					"data" => gettype($data) !== "string" ? $data : json_decode($data, true),
					"author" => $user['id']
				];
			}

			throw new \Exception("Failed add product, please try again", 500);

		} catch (Throwable $e) {
			throw $e;
		}
	}


	function update($data, $id)
	{
		if (!$this->module->getRoles()->isAllowed(1)) {
			throw new \Exception('Permission denied');
		}

		if (empty($id)) {
			throw new \Exception('Missing required parameter: id', 400);
		}

		$product = DB::table("products")->select(["id", "title", "price", "category", "description", "data", "author"])->where("id")->equals($id)->execute()->fetch(PDO::FETCH_ASSOC);

		if (!$product) {
			throw new \Exception('Product not found', 404);
		}

		if (gettype($data) == 'string') {
			$data = json_decode($data, true);
		}

		try {

			if ($data) {
				$cpWrapper = Box::module("Editor")->findComponent($data['components'], 'product-wrapper');
				$title = Box::module("Editor")->findComponent($cpWrapper['components'], 'product-title')['components'][0]['content'] ?? '';
				$price = Box::module("Editor")->findComponent($cpWrapper['components'], 'product-price')['components'][0]['content'] ?? '';
				$category = Box::module("Editor")->findComponent($cpWrapper['components'], 'product-category')['components'][0]['content'] ?? '';
				$description = Box::module("Editor")->findComponent($cpWrapper['components'], 'product-description')['components'][0]['content'] ?? '';
			} else {
				$request = Request::getInstance();
				$title = $request->title();
				$price = $request->price();
				$category = $request->category();
				$description = $request->description();
			}

			$user = Box::module("Auth")->getLogedinUser();

			if (!$user || !isset($user['id'])) {
				throw new \Exception("Not allowed !", 401);
			}


			if (strlen($title) < 5) {
				throw new \Exception('Missing required parameter: title, minimum 5 characters');
			}

			if (strlen($price) < 1 || !is_numeric(preg_replace("/[^0-9]/", "", $price))) {
				throw new \Exception('Missing required parameter: price, minimum 1 number');
			}

			if (strlen($category) < 3) {
				throw new \Exception('Missing required parameter: category, minimum 3 characters');
			}

			if (strlen($description) < 30) {
				throw new \Exception('Missing required parameter: description, minimum 30 characters');
			}

			$SQL = "UPDATE products SET `title` = :title, `price` = :price, `category` = :category, `description` = :description, " . ($data ? "`data` = :data, " : "") . " `author` = :author WHERE `id` = :id";
			$stmt = DB::instance()->prepare($SQL);
			return $stmt->execute([
				...[
					"id" => $id,
					"title" => $title,
					"price" => $price,
					"category" => $category,
					"description" => $description,
					"author" => $user['id']
				],
				...($data ? ["data" => gettype($data) === "string" ? $data : json_encode($data)] : [])
			]);

		} catch (Throwable $e) {
			throw $e;
		}
	}


	function delete($id)
	{

		if (!$this->module->getRoles()->isAllowed(1)) {
			throw new \Exception('Permission denied');
		}

		if (empty($id)) {
			throw new \Exception('Missing required parameter: id', 400);
		}

		try {
			$deleted = DB::table("products")->delete()->where("id")->equals($id)->execute();
			if ($deleted) {
				return [
					"code" => 200,
					"message" => "Product has been deleted successful"
				];
			}
			throw new \Exception("Failed delete product, please try again", 500);
		} catch (Throwable $e) {
			throw $e;
		}
	}

}