<?php

//$servername = "localhost";
//$username = "root";
//$password = "admin";
//$database = "semnomedb";

$servername = "localhost";
$username = "forge821_charles";
$password = "xEme?}^^J@d~";
$database = "forge821_e_so_marcar";

$sql = "INSERT INTO reserva (id_cliente, id_quadra, id_usuario, valor, data_hora_reserva, data_hora_insercao, marcacao_mensal)
                SELECT rm.* FROM 
                (SELECT                   
                r.id_cliente,
                r.id_quadra,
                r.id_usuario,
                r.valor,
                CONCAT(dias.d, ' ', r.hora_inicial) as data_hora_reserva, 
                current_timestamp() as data_hora_insercao,
                true as marcacao_mensal
                FROM horario r
                LEFT JOIN quadra q ON q.id = r.id_quadra
                LEFT JOIN cliente c ON c.id = r.id_cliente
                LEFT JOIN usuario u ON u.id = r.id_usuario
                LEFT JOIN dia_semana d ON d.id = r.dia_semana
                JOIN 
                (SELECT 
                                CAST((SYSDATE()+INTERVAL (H+T+U) DAY) AS date) as d,
                                CASE WHEN WEEKDAY(CAST((SYSDATE()+INTERVAL (H+T+U) DAY) AS date)) = 6 THEN 1 ELSE WEEKDAY(CAST((SYSDATE()+INTERVAL (H+T+U) DAY) AS date)) + 2 END as dow
                FROM ( SELECT 0 H
                        UNION ALL SELECT 100 UNION ALL SELECT 200 UNION ALL SELECT 300
                  ) H CROSS JOIN ( SELECT 0 T
                        UNION ALL SELECT  10 UNION ALL SELECT  20 UNION ALL SELECT  30
                        UNION ALL SELECT  40 UNION ALL SELECT  50 UNION ALL SELECT  60
                        UNION ALL SELECT  70 UNION ALL SELECT  80 UNION ALL SELECT  90
                  ) T CROSS JOIN ( SELECT 0 U
                        UNION ALL SELECT   1 UNION ALL SELECT   2 UNION ALL SELECT   3
                        UNION ALL SELECT   4 UNION ALL SELECT   5 UNION ALL SELECT   6
                        UNION ALL SELECT   7 UNION ALL SELECT   8 UNION ALL SELECT   9
                  ) U
                WHERE
                  (SYSDATE()+INTERVAL (H+T+U) DAY) <= (SYSDATE()+INTERVAL 1 WEEK)) as dias ON dias.dow = r.dia_semana
                WHERE r.id_usuario IN (SELECT h.id_usuario FROM semnomedb.horario h WHERE h.id_usuario IS NOT NULL))
                as rm
                LEFT JOIN reserva r ON r.data_hora_reserva = rm.data_hora_reserva AND r.id_quadra = rm.id_quadra
                WHERE r.id is null";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully\nRunning...\n";

if (mysqli_query($conn, $sql)) {
    echo "Success\n";
} else {
    echo "Error\n";
}

mysqli_close($conn);
