<?php
namespace MerapiPanel\Module\Article\Controller\Endpoint;

use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Http\Request;

class Update
{
    public function status(Request $req)
    {

        $id = $req->id();
        if (!$id || strlen($id) < 16) {
            return [
                "code" => 400,
                "message" => "id character is not possible to be less than 16",
            ];
        }
        $status = $req->status();
        if (!in_array($status, [0, 1])) {
            return [
                "code" => 400,
                "message" => "Invalid request, status must be 0 or 1 but " . $status . " given",
            ];
        }

        if (!DB::table("articles")->update(["status" => $status])->where("id")->equal($id)->execute()) {
            return [
                "code" => 500,
                "message" => "fail to update article",
            ];
        }

        return [
            "code" => 200,
            "message" => "success",
        ];
    }
}