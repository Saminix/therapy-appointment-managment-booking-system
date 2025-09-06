
<?php 
include 'includes/connection.php';
// Initialize the session
session_start();

// check if search form has been submitted
if(isset($_POST['search'])) {
    $rating = $_POST['rating'];
    // fetch reviews with the given rating from the database
    $query = "SELECT review.Review_ID, client.FirstName, client.LastName, review.Details, review.Star_Rating, review.Date
              FROM review 
              INNER JOIN appointment ON review.Appointment_ID = appointment.Appointment_ID
              INNER JOIN client ON appointment.Client_ID = client.Client_ID
              WHERE review.Star_Rating = '$rating'";
    $result = mysqli_query($conn, $query);
} else {
    // fetch all reviews from the database
    $query = "SELECT review.Review_ID, client.FirstName, client.LastName, review.Details, review.Star_Rating, review.Date
              FROM review 
              INNER JOIN appointment ON review.Appointment_ID = appointment.Appointment_ID
              INNER JOIN client ON appointment.Client_ID = client.Client_ID";
    $result = mysqli_query($conn, $query);
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Client Reviews</title>
	<link rel="stylesheet" href="stylesheets/clientReview.css">
    <?php include("includes/navigation.php")?>
</head>
<body>

	<div class="container">
		<h2>Client Reviews</h2>
	</div>
	<div class="filter">
		<form method="post" class="search">
			<label for="rating">Search by Star Rating:</label>
			<select name="rating">
				<option value="1">1 star</option>
				<option value="2">2 stars</option>
				<option value="3">3 stars</option>
				<option value="4">4 stars</option>
				<option value="5">5 stars</option>
			</select>
			<button type="submit"class="search"name="search">Search</button>
		</form>
	</div>
	<div class="review">
		<?php 
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				echo "<div class='comment'>";
				echo "<h3>".$row['FirstName']." ".$row['LastName']."</h3>";
				echo "<div class='review-date'>".$row['Date']."</div>";
				echo "<p>".$row['Details']."</p>";
				echo "<div class='star-rating' data-rating='".$row['Star_Rating']."'></div>";
				echo "</div>";
			}
		} else {
			echo "<p>No reviews found.</p>";
		}
		?>
	</div>

	<?php mysqli_close($conn); ?>

</body>
</html>
