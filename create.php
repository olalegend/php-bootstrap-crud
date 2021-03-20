<?php
require_once "config.php";
 
$name = $gender = $dob = $picture = $email = $password"";
$name_err = $gender_err = $dob_err = $picture_err = $email_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate gender
    $input_gender = trim($_POST["gender"]);
    if(empty($input_gender)){
        $gender_err = "Please enter gender.";     
    } else{
        $gender = $input_gender;
    }
	
	 // Validate dob
    $input_dob = trim($_POST["dob"]);
    if(empty($input_dob)){
        $dob_err = "Please enter dob.";     
    } else{
        $dob = $input_dob;
    }
	
	 // Validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter email.";     
    } else{
        $email = $input_email;
    }
	
	 // Validate password
    $input_password = trim($_POST["password"]);
    if(empty($input_password)){
        $password_err = "Please enter password.";     
    } else{
        $password = $input_password;
    }
	
	 // Validate picture
    $input_picture = $_FILES['picture']['name'];
	$allowed_pix = array('jpg', 'jpeg', 'png', 'gif');
	$img_ext = substr($_FILES['picture']['name'], strrpos($_FILES['picture']['name'], '.') + 1);
		
    if(empty($input_picture)){
        $picture_err = "Please select picture.";     
    } else{
		if(in_array($img_ext, $allowed_file)){
			$picture = move_uploaded_file($_FILES['picture']['tmp_name'], "stu_pictures/".$input_picture);
		}
    }
    
    
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($gender_err) && empty($picture_err)
	&& empty($dob_err) && empty($email_err)  && empty($password_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO students (name, gender, dob, picture, email, password) VALUES (?, ?, ?, ?, ?, ?)";
 
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("ssssss", $name, $gender, $dob, $picture, $email, md5($password));
           
            
            if($stmt->execute()){
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Student Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Student Record</h2>
                    </div>
                    <p>Please fill this form and submit to create new student</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($gender_err)) ? 'has-error' : ''; ?>">
                            <label>Gender</label>
							<select name="gender" id="gender">
							  <option value="male">Male</option>
							  <option value="female">Female</option>
							</select>
                            <span class="help-block"><?php echo $gender_err;?></span>
                        </div>
						
                        <div class="form-group <?php echo (!empty($dob_err)) ? 'has-error' : ''; ?>">
                            <label>DOB</label>
                            <input type="date" name="dob" class="form-control" value="<?php echo $dob; ?>"/>
                            <span class="help-block"><?php echo $dob_err;?></span>
                        </div>
						
						<div class="form-group <?php echo (!empty($picture_err)) ? 'has-error' : ''; ?>">
                            <label>Picture</label>
                            <input type="file" name="picture" class="form-control" value="<?php echo $picture; ?>"/>
                            <span class="help-block"><?php echo $picture_err;?></span>
                        </div>
						
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                            <span class="help-block"><?php echo $email_err;?></span>
                        </div>
						
						<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                            <span class="help-block"><?php echo $password_err;?></span>
                        </div>
						
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>