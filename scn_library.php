<?php
/*{
    error:1,
    message:"",
    result: "resutl may be array object or any thing"
}*/
class Error_api{
    public $error;
    public $message;
    public $result;
    function __construct($err,$msg,$result){
        $this->error = $err;
        $this->message = $msg;
        $this->result = $result;
    }
}

class Scn_connection{
    public $conn;
    function __construct($servername,$username,$password,$db=""){
        // Create connection, $db is optional
        if($db==""){
            $conn = new mysqli($servername, $username, $password);
        }
        else{
            $conn = new mysqli($servername, $username, $password,$db);
        }
        
        if ($conn->connect_error) {
            $this->conn=$conn->connect_error;
            $conn_return = new Error_api(1,"Connection failed",$conn->connect_error);
            return $conn_return;
            die(new Error_api(1,"Connection failed",$conn->connect_error));
        }
        else{
            $this->conn=$conn;
            $conn_return = new Error_api(0,"Connected Successfully",$conn);
            return $conn_return;
        }
    //Return : Return connection in $conn->result
    }

    function scn_create_database($db_name){
        $sql = "CREATE DATABASE ".$db_name;
        $conn=$this->conn;
        if ($conn->query($sql) === TRUE) {
            $conn_return = new Error_api(0,"Database created successfully","Database created successfully");
            return $conn_return;
        } else {
            $conn_return = new Error_api(1,"Error creating database", $conn->error);
            return $conn_return;
        }

        $conn->close();
    }

    function scn_create_table($sql){
        // Whole SQL query is needed to pass in 2nd parameter
        $conn=$this->conn;         
            if ($conn->query($sql) === TRUE) {
            return new Error_api(0,"Table MyGuests created successfully", "Table MyGuests created successfully");
            } else {
            return new Error_api(1,"Error creating table", $conn->error);
            }
            
            $conn->close();
    }

    function scn_insert_record($table_name,$json_details){ //(table_name[String], json_details[innput in json format {"field_Name":"Value"} note* only string])
        $conn=$this->conn;
        $field_ar=Array();
        $value_ar=Array();
        $json_details = str_replace(array("\n","\r"), '', $json_details);  // it remove new line from json (prevent error | error : wrong input for foreach function )
        $obj = json_decode($json_details, TRUE);
        foreach($obj as $key => $value) {
            array_push($field_ar,$key);
            array_push($value_ar,$value);
        }
        $sql = "INSERT INTO ".$table_name." (".implode($field_ar,',').")
            VALUES ('".implode($value_ar,'\',\'')."')";
            if ($conn->query($sql) === TRUE) {
                $conn_return = new Error_api(0,"New record created successfully", $conn->insert_id);
                return $conn_return;
            } else {
                $conn_return = new Error_api(1,"Error: " . $sql, $conn->error);
                return $conn_return;
            }

            $conn->close();
    }

    function scn_select_all($table_name, $where_exp="", $distinct=false, $orderby=""){   //(table_name, where_expression[optional, Write Expression only], distinct[optional, true/false], orderby[Column Name ->only one allowed])
        $conn=$this->conn;
        if($where_exp!=""){
            $where_exp=" WHERE ".$where_exp;
        }
        if($orderby!=""){
            $orderby=" ORDER BY ".$orderby;
        }
        if($distinct){
            $distinct="DISTINCT";
        }
        $sql = "SELECT ".$distinct." * FROM ".$table_name.$where_exp.$orderby;
        $result = $conn->query($sql);
        $result_ar=Array();
        if ($result->num_rows > 0) {
        //output data of each row
        for($i=0;$row[$i] = $result->fetch_assoc();$i++) {
            //echo "id: " . $row[$i]["id"]. " - Name: " . $row[$i]["firstname"]. " " . $row[$i]["lastname"]. "<br>";
            array_push($result_ar,$row[$i]);
        }
        return new Error_api(0,"Success",$result_ar);
        } else {
            return new Error_api(1,"No Result Found","No Result Found");
        }
        $conn->close();
    }

    function scn_select_column($table_name, $column_ar, $where_exp="", $distinct=false, $orderby=""){   //(table_name, columns[Array], where_expression[optional, Write Expression only], distinct[optional, true/false], orderby[Column Name ->only one allowed])
        $conn=$this->conn;
        if($where_exp!=""){
            $where_exp=" WHERE ".$where_exp;
        }
        if($orderby!=""){
            $orderby=" ORDER BY ".$orderby;
        }
        if($distinct){
            $distinct="DISTINCT";
        }
        $sql = "SELECT ".$distinct." ".implode($column_ar,',')." FROM ".$table_name.$where_exp.$orderby;
        $result = $conn->query($sql);
        $result_ar=Array();
        if ($result && $result->num_rows > 0) {
        //output data of each row
        for($i=0;$row[$i] = $result->fetch_assoc();$i++) {
            //echo "id: " . $row[$i]["id"]. " - Name: " . $row[$i]["firstname"]. " " . $row[$i]["lastname"]. "<br>";
            array_push($result_ar,$row[$i]);
        }
        return new Error_api(0,"Success",$result_ar);
        } else {
            return new Error_api(1,"No Result Found","No Result Found");
        }
        $conn->close();
    }

    function scn_delete_record($table_name, $where_exp){  //(table name, Where expression )
        $conn=$this->conn;
        $sql = "DELETE FROM ".$table_name." WHERE ".$where_exp;
        if ($conn->query($sql) === TRUE) {
            return new Error_api(0,"Record deleted successfully","Record deleted successfully");
        } else {
            return new Error_api(1,"Error deleting record",$conn->error);
        }

        $conn->close();
    }

    function scn_update_record($table_name,$json_details, $where_exp){ //(table_name[String], json_details[innput in json format {"field_Name":"Value"} note* only string], where expression [Only expression not 'WHERE'])
        $conn=$this->conn;
        $field_ar=Array();
        $json_details = str_replace(array("\n","\r"), '', $json_details);  // it remove new line from json (prevent error | error : wrong input for foreach function )
        $obj = json_decode($json_details, TRUE);
        foreach($obj as $key => $value) {
            array_push($field_ar,$key."='".$value."'");
        }
        $sql = "UPDATE ".$table_name." SET ".implode($field_ar,",")." WHERE ".$where_exp;
            if ($conn->query($sql) === TRUE) {
                $conn_return = new Error_api(0,"Record Updated successfully", "Record Updated successfully");
                return $conn_return;
            } else {
                $conn_return = new Error_api(1,"Error: " . $sql, $conn->error);
                return $conn_return;
            }

            $conn->close();
    }
}

//$connection = new Scn_connection("localhost","root","","newdbscn");
//print_r($connection->scn_create_table("CREATE TABLE MyGuests (    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,    firstname VARCHAR(30) NOT NULL,    lastname VARCHAR(30) NOT NULL,    email VARCHAR(50),    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)"));

//print_r($connection->scn_insert_record('myguests','{"firstname":"Sachin","lastname":"Thakur","email":"scn.arn@gmail.com"}'));

// $temp_result=$connection->scn_select_all("myguests","id>7",true,"firstname");//(table_name, where_expression,distinct,orderby)
    // print_r($temp_result->result[2]["firstname"]);

//  $temp_result=$connection->scn_select_column("myguests",Array("firstname","lastname"));//(table_name, where_expression,distinct,orderby)
//      print_r($temp_result->result[0]);

// $temp = $connection->scn_delete_record("myguests","id=8"); //be carefule in 2nd parameter
// print_r($temp);

// $temp = $connection->scn_update_record('myguests','{"firstname":"Scn","lastname":"arayans"}','id=10');
// print_r($temp);
?>