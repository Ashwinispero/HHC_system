<?php

include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{

    $query = mysql_query("SELECT * FROM sp_hospitals WHERE status = 1 ");
    $row_count = mysql_num_rows($query);

    if ($row_count > 0)
    {
        $rows = array();
        while ($row = mysql_fetch_assoc($query))
        {

            $hospital_id = $row['hospital_id'];
            $hospital_id = (int)$hospital_id;
            $hospital_name = $row['hospital_name'];

            $result[] = (array(
                'hos_id' => $hospital_id,
                'hos_name' => $hospital_name
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
