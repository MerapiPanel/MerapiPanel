<?php
namespace MerapiPanel\Module\Contact\Controller;

use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;

class Guest extends __Controller
{

	function register()
	{
		Router::GET("/contact/link/redirect/TP-{id}", "withTemplate", self::class);
		Router::GET("/contact/link/redirect/CT-{id}", "contact", self::class);
		// register other route
	}



	function withTemplate(Request $request, Response $response)
	{

		$id = $request->id();
		$template = $this->getModule()->Template->fetch($id);

		if (!$template) {
			$default = $this->getModule()->default();
			if (!$default) {
				throw new \Exception("Template not found", 404);
			}
			return $this->send($default["type"], $response, [
				"contact_id" => $default["id"],
				"address" => $default["address"],
			]);
		}
		if (!$template["contact"]) {
			$default = $this->getModule()->default();
			if (!$default) {
				throw new \Exception("Template not found", 404);
			}
			$template["contact"] = $default;
		}

		$payload = [
			"contact_id" => $template["contact"]["id"],
			"address" => $template["contact"]["address"],
			"subject" => $template["data"]["subject"] ?? "",
			"message" => $template["data"]["message"] ?? "",
		];
		return $this->send($template["contact"]["type"], $response, $payload);
	}



	function contact(Request $request, Response $response)
	{
		$id = $request->id();
		$contact = $this->getModule()->fetch($id);
		if (!$contact) {
			$contact = $this->getModule()->default();
			if (!$contact) {
				throw new \Exception("Template not found", 404);
			}
		}
		return $this->send($contact["type"], $response, [
			"contact_id" => $id,
			"address" => $contact["address"],
		]);
	}




	function send($type, Response $response, $payload = [])
	{


		if (!in_array($type, ["email", "phone", "whatsapp"])) {
			throw new \Exception("Invalid type", 401);
		}

		$types = [
			"email" => "mailto:{address}?subject={subject}&body={message}",
			"phone" => "tel:{address}",
			"whatsapp" => "https://wa.me/{address}/?text={message}",
		];

		$url = $types[$type];
		$payload = [
			"contact_id" => $payload["contact_id"] ?? null,
			"address" => $payload["address"] ?? "",
			"subject" => $payload["subject"] ?? "",
			"message" => $payload["message"] ?? "",
		];

		foreach ($payload as $key => $value) {
			if ($type == "whatsapp" && $key == "message") {
				$value = $this->htmlToWhatsappMarkdown($value);
				$value = rawurlencode($value);
			}
			if ($type == "email" && $key == "message") {
				// Encode HTML content and prepend data URL
				$value = $this->replaceHtml($value);
				$value = rawurlencode($value);
			}
			if ($key == "subject") {
				$value = rawurlencode($value);

			}
			$url = str_replace("{" . $key . "}", $value, $url);
		}


		$response->setHeader("Location", $url);

		try {
			if (isset($payload['contact_id']) && !empty($payload['contact_id'])) {
				$client_ip = Request::getClientIP();
                $client_ip = $client_ip == '::1' ? '127.0.0.1' : $client_ip;
				$this->getModule()->Logs->write($client_ip, $payload['contact_id']);
			}
		} catch (\Exception $e) {
			// Do nothing
			error_log($e->getMessage());
		}
		return $response;
	}


	private function replaceHtml($html)
	{
		// Replace <p> tags with \n
		$html = preg_replace('/<p[^>]*>(.*?)<\/p>/s', "$1\n", $html);
		// Replace <div> tags with \n
		$html = preg_replace('/<div[^>]*>(.*?)<\/p>/s', "$1\n", $html);
		// Replace <br> tags with \n
		$html = preg_replace('/<br[^>]*>/s', "\n", $html);
		// Remove all remaining HTML tags
		$html = strip_tags($html);
		return $html;
	}

	private function htmlToWhatsappMarkdown($html)
	{
		// Replace <i> and <em> tags with _
		$html = preg_replace('/<(i|em)[^>]*>(.*?)<\/\1>/s', '_$2_', $html);

		// Replace <b> and <strong> tags with *
		$html = preg_replace('/<(b|strong)[^>]*>(.*?)<\/\1>/s', '*$2*', $html);
		// Replace <strike>, <s>, and <u> tags with ~
		$html = preg_replace('/<strike[^>]*>(.*?)<\/\1>/s', '~$2~', $html);
		// Replace <del>, <s>, and <u> tags with ~
		$html = preg_replace('/<del[^>]*>(.*?)<\/del>/s', '~$1~', $html);
		// Replace <del>, <s>, and <u> tags with ~
		$html = preg_replace('/<s>(.*?)<\/s>/s', '~$1~', $html);
		// Replace <u> tags with ~
		$html = preg_replace('/<u>(.*?)<\/u>/s', '~$1~', $html);
		// Replace <code> tags with ```
		$html = preg_replace('/<code[^>]*>(.*?)<\/code>/s', '```$1```', $html);
		// Replace <ul> tags with *
		$html = preg_replace_callback('/<ul[^>]*>(.*?)<\/ul>/s', function ($matches) {
			$listItems = preg_replace('/<li[^>]*>(.*?)<\/li>/s', "* $1\n", $matches[1]);
			return $listItems;
		}, $html);
		// Replace <ol> tags with numbered list
		$html = preg_replace_callback('/<ol[^>]*>(.*?)<\/ol>/s', function ($matches) {
			$listItems = preg_replace_callback('/<li[^>]*>(.*?)<\/li>/s', function ($matches) {
				static $count = 1;
				return $count++ . ". $matches[1]\n";
			}, $matches[1]);
			return $listItems;
		}, $html);
		// Replace <blockquote> tags with >
		$html = preg_replace('/<blockquote[^>]*>(.*?)<\/blockquote>/s', "> $1\n", $html);
		// Replace <p> tags with \n
		$html = preg_replace('/<p[^>]*>(.*?)<\/p>/s', "$1\n", $html);
		// Replace <br> tags with \n
		$html = preg_replace('/<br\s*\/?>/s', "\n", $html);
		// Replace <hr> tags with \n
		$html = preg_replace('/<hr\s*\/?>/s', "\n", $html);
		// Replace <h1> to <h6> tags with *
		$html = preg_replace('/<h([1-6])[^>]*>(.*?)<\/h\1>/s', "*$2*\n", $html);
		// Remove all remaining HTML tags
		$html = strip_tags($html);
		return $html;
	}
}