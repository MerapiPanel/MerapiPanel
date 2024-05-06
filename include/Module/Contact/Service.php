<?php
namespace MerapiPanel\Module\Contact;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;
use MerapiPanel\Database\DB;
use PDO;

class Service extends __Fragment
{

	protected Module $module;
	function onCreate(Module $module)
	{
		$this->module = $module;
	}



	function count($contactType = null)
	{
		$SQL = "SELECT COUNT(id) FROM contacts" . (
			in_array($contactType, ["phone", "email", "whatsapp"]) ? " WHERE type = :type" : ""
		);
		$stmt = DB::instance()->prepare($SQL);
		if (in_array($contactType, ["phone", "email", "whatsapp"])) {
			$stmt->execute(['type' => $contactType]);
		} else {
			$stmt->execute();
		}
		return $stmt->fetchColumn();
	}




	public function fetchAll($contactType = null)
	{
		$SQL = "SELECT * FROM contacts" . (
			in_array($contactType, ["phone", "email", "whatsapp"]) ? " WHERE type = :type" : ""
		) . " ORDER BY name";
		$stmt = DB::instance()->prepare($SQL);
		if (in_array($contactType, ["phone", "email", "whatsapp"])) {
			$stmt->execute(['type' => $contactType]);
		} else {
			$stmt->execute();
		}
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	

	public function fetch($id)
	{
		$SQL = "SELECT * FROM contacts WHERE id = :id";
		$stmt = DB::instance()->prepare($SQL);
		$stmt->execute(['id' => $id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}



	function add($type, $address, $name)
	{

		if (!in_array($type, ["phone", "email", "whatsapp"])) {
			throw new \Exception("Invalid contact type");
		}
		if (empty($address)) {
			throw new \Exception("Invalid contact address");
		}
		if (empty($name)) {
			throw new \Exception("Invalid contact name");
		}

		$SQL = "INSERT INTO contacts (type, address, name) VALUES (:type, :address, :name)";
		$stmt = DB::instance()->prepare($SQL);
		if (
			$stmt->execute([
				'type' => $type,
				'address' => $address,
				'name' => $name
			])
		) {
			return [
				"id" => DB::instance()->lastInsertId(),
				"type" => $type,
				"address" => $address,
				"name" => $name
			];
		}

		throw new \Exception("Failed to add contact");
	}



	function update($id, $type, $address, $name)
	{

		if (!in_array($type, ["phone", "email", "whatsapp"])) {
			throw new \Exception("Invalid contact type");
		}
		if (empty($address)) {
			throw new \Exception("Invalid contact address");
		}
		if (empty($name)) {
			throw new \Exception("Invalid contact name");
		}

		$SQL = "UPDATE contacts SET type = :type, address = :address, name = :name, update_date = NOW() WHERE id = :id";
		$stmt = DB::instance()->prepare($SQL);
		if (
			$stmt->execute([
				'id' => $id,
				'type' => $type,
				'address' => $address,
				'name' => $name
			])
		) {
			return [
				"id" => $id,
				"type" => $type,
				"address" => $address,
				"name" => $name
			];
		}

		throw new \Exception("Failed to update contact");
	}



	function delete($id)
	{
		$SQL = "DELETE FROM contacts WHERE id = :id";
		$stmt = DB::instance()->prepare($SQL);
		return $stmt->execute(['id' => $id]);
	}



	function default()
	{
		$SQL = "SELECT * FROM contacts WHERE is_default = 1";
		$stmt = DB::instance()->prepare($SQL);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}




	function setDefault($id)
	{
		$SQL = "UPDATE contacts SET is_default = 0";
		$stmt = DB::instance()->prepare($SQL);
		$stmt->execute();
		$SQL = "UPDATE contacts SET is_default = 1 WHERE id = :id";
		$stmt = DB::instance()->prepare($SQL);
		return $stmt->execute(['id' => $id]);
	}

}