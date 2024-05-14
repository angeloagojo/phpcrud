<?php
require_once('classes/database.php');
$con = new database();

session_start();

if (empty($_SESSION['user'])) {
  header('location:login.php');
  }
  

$id = $_POST['id'];
if(empty($id)){
    header('location:index.php');
} else {
  $row=$con->viewdata($id);
}

if(isset($_POST['update'])){
  $id = $_POST['id'];
  $firstname= $_POST['first'];
  $lastname = $_POST['last'];
  $birthday = $_POST['birthday'];
  $sex = $_POST['sex'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirm = $_POST['c_pass'];

  $street = $_POST['street'];
  $barangay = $_POST['barangay'];
  $city = $_POST['city'];
  $province = $_POST['province'];

  if($password==$confirm){
    if($con->updateUser($id,$firstname, $lastname, $birthday, $sex, $username, $password)){
      if($con->updateUserAddress($id,$street,$barangay,$city,$province)){
        header('location:index.php');
        exit();
      } else {
        $error = "Error while updating user address. Please try again.";
      }
    } else{
      $error = "Error occured while updating user information. Please try again.";
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Page</title>
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    .custom-container{
        width: 800px;
    }
    body{
    font-family: 'Roboto', sans-serif;
    }
  </style>

</head>
<body>

<?php include('includes/navbar.php');?>

<div class="container custom-container rounded-3 shadow my-5 p-3 px-5">
  <h3 class="text-center mt-4"> User Profile</h3>
  <form method="post">
    <!-- Personal Information -->
    <div class="card mt-4">
      <div class="card-header bg-info text-white">Personal Information</div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-6 col-sm-12">
            <label for="firstName">First Name:</label>
            <input type="text" class="form-control" name="first" value="<?php echo ucwords($row['first_name']); ?>" placeholder="Enter first name" required>
          </div>
          <div class="form-group col-md-6 col-sm-12">
            <label for="lastName">Last Name:</label>
            <input type="text" class="form-control" name="last" value="<?php echo ucwords($row['last_name']); ?>" placeholder="Enter last name" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="birthday">Birthday:</label>
            <input type="date" class="form-control" value="<?php echo $row['birthdate']; ?>" name="birthday" required>
          </div>
          <div class="form-group col-md-6">
            <label for="sex">Sex:</label>
            <select class="form-control" value="<?php echo $row['sex']; ?>" name="sex" required>  
              <option selected>Select Sex</option>
              <option value="Male" <?php if($row['sex'] == 'Male') echo 'selected'?>>Male</option>
              <option value="Female" <?php if($row['sex'] == 'Female') echo 'selected'?>>Female</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="username">Userna            </label>
          <input type="text" class="fo            control" name="username" value="<?php echo $row['user']; ?>" placeholder="Enter username" required>
        </div>
        <div class="form-group">
          <label for="password">Passwo            </label>
          <input type="password" class            orm-control" name="password" value="<?php echo $row['pass']; ?>" placeholder="Enter password" required>
        </div>
        <div class="form-group">
          <label for="password">Confirm Password:</label>
          <input type="password" class="form-control" name="c_pass" placeholder="Re-Enter password" required>
        </div>
      </div>
    </div>
   
    <!-- Address Information -->
    <div class="card mt-4">
      <div class="card-header bg-success text-white">Address Information</div>
      <div class="card-body">
        <div class="form-group">
          <label for="street">Street:</label>
          <input type="text" class="form-control" name="street" value="<?php echo ucwords($row['user_street']); ?>" placeholder="Enter street" required>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="barangay">Barangay:</label>
            <input type="text" class="form-control" name="barangay" value="<?php echo ucwords($row['user_barangay']); ?>" placeholder="Enter barangay" required>
          </div>
          <div class="form-group col-md-6">
            <label for="city">City:</label>
            <input type="text" class="form-control" name="city" value="<?php echo ucwords($row['user_city']); ?>" placeholder="Enter city" required>
          </div>
        </div>
        <div class="form-group">
          <label for="province">Province:</label>
          <input type="text" class="form-control" name="province" value="<?php echo ucwords($row['user_province']); ?>" placeholder="Enter province" required>
        </div>
      </div>
    </div>
    
    <!-- Submit Button -->
    <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php endif; ?>
                   
    <div class="container">
    <div class="row justify-content-center gx-0">
        <div class="col-lg-3 col-md-4"> 
        <input type="hidden" name="id" value="<?php echo $row['user_id']; ?>"> 
            <input type="submit" name="update" class="btn btn-outline-primary btn-block mt-4" value="Update Profile">
        </div>
        <div class="col-lg-3 col-md-4"> 
            <a class="btn btn-outline-danger btn-block mt-4" href="index.php">Go Back</a>
        </div>
    </div>
</div>


  </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
<!-- Bootsrap JS na nagpapagana ng danger alert natin -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
