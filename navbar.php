<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name ="viewport" content="with=device-width, inital-scale-1.0">

<div class="header">
<div class="navigationbar">
  <nav>
  <div class="header">
<div class="navigationbar">
	<nav>
		<div class="navbar-links">
		<ul>
			<li class="Navbar"><a href="index.php">Home</a></li>
			<li class="Navbar"><a href="">profile.html</a></li>
			<li class="Navbar"><a href="index.php">logout</a></li>
		</ul>
		</div>
	</nav>
</div>
<body>
<html">

<style>
*{
	margin: 0;
	padding: 0;/* CSS Document */
}

.navigationbar{
	text-align: center;

}

nav{
	display: flex;
	padding: 2% 5%;
	justify-content: space-between;
	align-items: center;
}

.navbar-links{
	flex: 1;
	text-align: left;
}
	
.navbar-links ul li {
	list-style: none;
	display: inline-block;
	padding: 8px 12px;
	position: relative;
}

.navbar-links ul li a{
    text-decoration: none;
    font-size: 25px;
    font-family: "Helvetica Neue";
    color: black;
	font-weight: bold;
}

.navbar-links ul li::after{
	content: '';
	width: 0%;
	height: 2px;
	background: #B46D00;
	display: block;
	margin: auto;
	transition: 0.6s;
}
.navbar-links ul li:hover::after{
	width: 99%;
}

</style>
</div>