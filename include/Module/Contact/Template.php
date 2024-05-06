<?php

namespace MerapiPanel\Module\Contact {
    use MerapiPanel\Box\Module\__Fragment;
    use MerapiPanel\Database\DB;

    class Template extends __Fragment
    {
        protected $module;
        function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
        {
            $this->module = $module;
        }




        function count()
        {
            $SQL = "SELECT COUNT(id) FROM contacts_template";
            $stmt = DB::instance()->prepare($SQL);
            $stmt->execute();
            return $stmt->fetchColumn();
        }




        function fetchAll()
        {
            $SQL = "SELECT * FROM contacts_template ORDER BY post_date DESC";
            $stmt = DB::instance()->prepare($SQL);
            $stmt->execute();
            $templates = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if ($templates) {
                foreach ($templates as &$template) {
                    $template['contact'] = $this->module->fetch($template['contact_id']);
                    $template['data'] = json_decode($template['data'], true);
                }
                return $templates;
            }

            return [];
        }



        function fetch($id)
        {
            $SQL = "SELECT * FROM contacts_template WHERE id = :id";
            $stmt = DB::instance()->prepare($SQL);
            $stmt->execute([':id' => $id]);
            $template = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($template) {
                $template['contact'] = $this->module->fetch($template['contact_id']);
                $template['data'] = json_decode($template['data'], true);
            }

            return $template;
        }




        function delete($id)
        {
            $SQL = "DELETE FROM contacts_template WHERE id = :id";
            $stmt = DB::instance()->prepare($SQL);
            $stmt->execute([':id' => $id]);
        }


        

        function add($name, $contact, $message, $subject)
        {

            $contact = $this->module->fetch($contact);
            if (!$contact) {
                throw new \Exception("Invalid contact");
            }

            $contact_id = $contact['id'];
            $contact_type = $contact['type'];

            $data = [];
            if ($contact_type == 'email') {
                $data = [
                    'subject' => $subject,
                    'message' => $message
                ];
            } else if ($contact_type == 'whatsapp') {
                $data = [
                    "message" => $message
                ];
            }

            $SQL = "INSERT INTO contacts_template (name, contact_id, data) VALUES (:name, :contact_id, :data)";
            $stmt = DB::instance()->prepare($SQL);
            if (
                $stmt->execute([
                    ':name' => $name,
                    ':contact_id' => $contact_id,
                    ':data' => is_array($data) ? json_encode($data) : $data
                ])
            ) {
                return [
                    'id' => DB::instance()->lastInsertId(),
                    'name' => $name,
                    'contact_id' => $contact_id,
                    'data' => $data
                ];
            }

            throw new \Exception("Error Processing Request");
        }



        function update($id, $name, $contact, $message, $subject)
        {
            $contact = $this->module->fetch($contact);
            if (!$contact) {
                throw new \Exception("Invalid contact");
            }

            $contact_id = $contact['id'];
            $contact_type = $contact['type'];

            $data = [];
            if ($contact_type == 'email') {
                $data = [
                    'subject' => $subject,
                    'message' => $message
                ];
            } else if ($contact_type == 'whatsapp') {
                $data = [
                    "message" => $message
                ];
            }

            $SQL = "UPDATE contacts_template SET name = :name, contact_id = :contact_id, data = :data, update_date = NOW() WHERE id = :id";
            $stmt = DB::instance()->prepare($SQL);
            if (
                $stmt->execute([
                    ':name' => $name,
                    ':contact_id' => $contact_id,
                    ':data' => is_array($data) ? json_encode($data) : $data,
                    ':id' => $id
                ])
            ) {
                return [
                    'id' => $id,
                    'name' => $name,
                    'contact_id' => $contact_id,
                    'data' => $data
                ];
            }

            throw new \Exception("Error Processing Request");
        }
    }
}