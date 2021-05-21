<?php

include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{

    $query = mysql_query("SELECT * FROM sp_caller_relation WHERE status = 1 ");
    $row_count = mysql_num_rows($query);

    if ($row_count > 0)
    {
        $rows = array();
        while ($row = mysql_fetch_assoc($query))
        {

            $relation_id = $row['relation_id'];
            $relation_id = (int)$relation_id;
            $relation = $row['relation'];

            $result[] = (array(
                'rel_id' => $relation_id,
                'rel_name' => $relation
            ));

        }

        $data = $result;
        echo json_encode(array(
            "data" => $data,
            "error" => null
        ));

    }

}
else
{
    http_response_code(405);

    echo json_encode(array(
        "data" => null,
        "error" => array(
            "message" => "Invalid_method call"
        )
    ));
}
?>
