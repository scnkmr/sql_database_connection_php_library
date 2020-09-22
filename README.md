It is made to DRY [Do not Repeat Yourself]
used to establish connection to sql server and perform various functionlities

Tutorial
Q1: How to Make connection?
Ans: 
$connection = new Scn_connection("localhost","root","","newdbscn");
//1st parameter => hostname
//2nd parameter => username
//3rd parameter => password
//fourth parameter => databasename  [Optional you can leave it blank if you have not created database yet]

Q2: How to Create Database?
Ans: 
$connection = new Scn_connection("localhost","root","");  //Establishing Connection

$temp = $connection->scn_create_database($database_name);
 print_r($temp)
 //1st parameter => database name
//this will return an object
/*{
    error:0,1 (error will be 1 if occured)
    message: (Message by author of library)
    result: (error message if error occured Otherwise it will return result, it may be object, array or any string and number)
} */

Q3: How to create table?
Ans: 
$connection = new Scn_connection("localhost","root","","newdbscn");
$temp = $connection->scn_create_table("CREATE TABLE MyGuests (    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,    firstname VARCHAR(30) NOT NULL,    lastname VARCHAR(30) NOT NULL,    email VARCHAR(50),    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)");
print_r($temp);

//1st parameter => SQl Queryin String
//for example 
//$sql="CREATE TABLE MyGuests (    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,    firstname VARCHAR(30) NOT NULL,    lastname VARCHAR(30) NOT NULL,    email VARCHAR(50),    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)"

Q4: How to insert Data into table?
Ans:
$connection = new Scn_connection("localhost","root","","newdbscn");

$connection->scn_insert_record('myguests','{"firstname":"Sachin","lastname":"Thakur","email":"scn.arn@gmail.com"}')

//1st parameter => table name
//2nd parameter => json format {"key":"value"} where key is field name & value contaiin value of that field

Q5: how to select all database?
Ans:
$connection = new Scn_connection("localhost","root","","newdbscn");

$temp_result=$connection->scn_select_all("myguests","id>7",true,"firstname");//(table_name, where_expression,distinct,orderby)
print_r($temp_result->result[2]["firstname"]);

//1st parameter => table name
//2nd parameter => where expression [optional]
//3rd parameter => distinct [set "true" if you want to select distinct]
//4th parameter => order by [pass column name with which you want to order your result]

//return Error_api mentioned in Q2

Q6: how to select selected column of database?
Ans:
$connection = new Scn_connection("localhost","root","","newdbscn");

$temp_result=$connection->scn_select_column("myguests",Array("firstname","lastname"));//(table_name, where_expression,distinct,orderby)
 print_r($temp_result->result[0]);

//1st parameter => table name
//2nd parameter => Array of coulmn name
//3rd parameter => where expression [optional]
//4th parameter => distinct [set "true" if you want to select distinct]
//5th parameter => order by [pass column name with which you want to order your result]

//return Error_api mentioned in Q2

Q7: How to delete record?
Ans:
$connection = new Scn_connection("localhost","root","","newdbscn");

$temp = $connection->scn_delete_record("myguests","id=8"); //be carefule in 2nd parameter 
print_r($temp);
//1st parameter => table name
//2nd parameter => where expression

//return Error_api mentioned in Q2

Q8: How to update record?
Ans:
$connection = new Scn_connection("localhost","root","","newdbscn");
$temp = $connection->scn_update_record('myguests','{"firstname":"Scn","lastname":"arayans"}','id=10');
print_r($temp);

//1st parameter => table name
//2nd parameter => json format details {"field_name":"value"}
//3rd parameter => where expression