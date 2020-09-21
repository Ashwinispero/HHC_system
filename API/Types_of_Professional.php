<?php

include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{

    $query = mysql_query("SELECT service_id,service_title FROM sp_services WHERE flag = 2 ");
    $row_count = mysql_num_rows($query);

    if ($row_count > 0)
    {
        $rows = array();
        while ($row = mysql_fetch_assoc($query))
        {

            $S_id = $row['service_id'];
            $S_id = (int)$S_id;
            $S_name = $row['service_title'];

            $result[] = (array(
                'id' => $S_id,
                'name' => $S_name
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
