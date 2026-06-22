# Project-Overview: 
It is a User Registation Foarm, Data Stored in Mysql DB, Frontend [Apache], Entaire Application Access through, Loadbalancer and DNS And Access Through, https[ In secured way].
1.Servers.1 [RHEL-9, SERVER]
2.Classic Loadbalancer.
3.Route53
4.RDS [mysql]
5.ACM-Service

# Install Apache and PHP
1. Update the system packages:
```
sudo dnf update -y
```
2. Install Apache web server and PHP with MySQL support:
```
sudo dnf install httpd php php-mysqlnd -y
```
3. Enable Apache to start automatically after reboot:
```
sudo systemctl enable httpd
```
4. Start the Apache service:
```
sudo systemctl start httpd
```
5. Verify that Apache is running:
```
sudo systemctl status httpd
```
# Install MySQL Client and Configure RDS Connectivity
6. Install the MySQL client.
```
sudo yum install mysql -y
mysql --version
```
7. Retrieve the Security Group attached to the RDS instance.
```
aws rds describe-db-instances \
  --db-instance-identifier user-db \
  --query 'DBInstances[0].VpcSecurityGroups[*].VpcSecurityGroupId' \
  --output table
```
8. Configure AWS CLI.
```
awws Configure
```
Provide:

AWS Access Key
AWS Secret Access Key
Default Region
Output Format
9. Display EC2 security groups.
```
aws ec2 describe-security-groups \
  --group-names ec2-rds-3 launch-wizard-1 \
  --output table
```
10. Test connectivity to MySQL port 3306.
```
telnet user-db.csj86e8qy90h.us-east-1.rds.amazonaws.com 3306
```
11.Allow EC2 to Access RDS.
```
aws ec2 authorize-security-group-ingress \
  --group-id sg-0c1cff026a6715b3d \
  --protocol tcp \
  --port 3306 \
  --source-group sg-0e153c7444b38257f
```
12. Connect to the RDS instance:
```
mysql -h <RDS-ENDPOINT> -u admin -p
```
13. Create a database:
```
CREATE DATABASE userdb;

USE userdb;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    mobile VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
14. Use the database:
```
DESC users;
```
15. Create the project directory:
```
sudo mkdir -p /var/www/html/userapp
```
16. Move into the directory:
```
cd /var/www/html/userapp
```
17. Create the database configuration file:
```
sudo vi db.php
```
```
<?php

$servername = "database-1.csj86e8qy90h.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "admin123";
$dbname = "userdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```
18. sudo vi index.php
```
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Registration</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

body{
    background: linear-gradient(to right,#141e30,#243b55);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.container{
    width:420px;
    background:white;
    padding:40px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
}

h1{
    text-align:center;
    color:#243b55;
    margin-bottom:10px;
}

.subtitle{
    text-align:center;
    color:gray;
    margin-bottom:25px;
}

.form-group{
    margin-bottom:20px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
}

input{
    width:100%;
    padding:14px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:15px;
}

input:focus{
    outline:none;
    border-color:#243b55;
}

button{
    width:100%;
    padding:15px;
    background:#243b55;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#141e30;
}

.footer{
    text-align:center;
    margin-top:20px;
    color:gray;
    font-size:13px;
}

</style>
</head>

<body>

<div class="container">

<h1>User Registration</h1>

<div class="subtitle">
AWS EC2 + RDS MySQL Demo
</div>

<form action="save.php" method="POST">

<div class="form-group">
<label>Full Name</label>
<input type="text" name="name" placeholder="Enter your name" required>
</div>

<div class="form-group">
<label>Email Address</label>
<input type="email" name="email" placeholder="Enter your email address" required>
</div>

<div class="form-group">
<label>Mobile Number</label>
<input type="text" name="mobile" placeholder="Enter your mobile number" required>
</div>

<button type="submit">
Register User
</button>

</form>

<div class="footer">
Powered by PHP + Apache + AWS RDS
</div>

</div>

</body>
</html>
```
19. sudo vi save.php
```
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['mobile']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];

    $sql = "INSERT INTO users(name,email,mobile)
            VALUES('$name','$email','$mobile')";

    if($conn->query($sql) === TRUE)
    {
        echo "
        <!DOCTYPE html>
        <html>
        <head>
        <title>Success</title>

        <style>

        body{
            background:#f4f6f9;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            font-family:Arial;
        }

        .card{
            background:white;
            padding:40px;
            border-radius:15px;
            box-shadow:0 10px 30px rgba(0,0,0,.2);
            text-align:center;
            width:400px;
        }

        h2{
            color:green;
            margin-bottom:20px;
        }

        p{
            margin-bottom:25px;
            color:#555;
        }

        a{
            text-decoration:none;
            background:#243b55;
            color:white;
            padding:12px 20px;
            border-radius:8px;
        }

        a:hover{
            background:#141e30;
        }

        </style>

        </head>

        <body>

        <div class='card'>

        <h2>✓ User Registered Successfully</h2>

        <p>Your details have been stored in MySQL RDS database.</p>

        <a href='index.php'>Register Another User</a>

        </div>

        </body>
        </html>
        ";
    }
    else
    {
        echo "Database Error : " . $conn->error;
    }
}
else
{
    echo "Invalid Request";
}

$conn->close();

?>
```
20. Assign ownership to Apache:
```
sudo chown -R apache:apache /var/www/html/userapp
```
21. Set appropriate permissions:
 ```
sudo chmod -R 755 /var/www/html/userapp
```
22. Access the Application.
```
http://<EC2-PUBLIC-IP>/userapp/index.php
```
