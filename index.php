<?php
	if(isset($_POST['geojson-input'])) {
		$json = json_decode($_POST['geojson-input']);
		$polygon_array = $json->features;

		$i = 1;
		$php_string = "\$polygon_array = array(<br>";
		foreach($polygon_array as $polygon) {

			$coordinates_array_pointer = 1;
			$coordinates_array = $polygon->geometry->coordinates[0];
			
			$properties_array = (array) $polygon->properties;

			$php_string .= "&#09;'POLYGON$i' => array(<br>";
			$google_string .= "var POLYGON$i = [<br>";
			foreach($coordinates_array as $coordinates) {

				if($coordinates_array_pointer == count($coordinates_array)) {
					$php_string .= "&#09;&#09;'" . $coordinates['1'] . ", " . $coordinates['0'] . "',<br>";
					$google_string .= "&#09;new google.maps.LatLng(" . $coordinates['1'] . ", " . $coordinates['0'] . ")<br>";
				} else {
					$php_string .= "&#09;&#09;'" . $coordinates['1'] . ", " . $coordinates['0'] . "',<br>";
					$google_string .= "&#09;new google.maps.LatLng(" . $coordinates['1'] . ", " . $coordinates['0'] . "),<br>";
				}

				$coordinates_array_pointer++;
			}

			$php_string .= "&#09;),<br>";
			$google_string .= "];<br><br>";
			
			// build maps properties object
			$properties_iterator = 1;
			$properties_len = count($properties_array);
			$polygon_shape_string = "POLYGONSHAPE" . $i . " = new google.maps.Polygon({<br>";
			foreach ($properties_array as $key => $property) {
			    $polygon_shape_string .= "&#09;" . $key . ": '" . $property . "'";
			    if ($properties_iterator == $properties_len) {
			        $polygon_shape_string .= "<br>";
			    } else {
			    	$polygon_shape_string .= ",<br>";
			    }
			    $properties_iterator++;
			}
			$polygon_shape_string .= "});<br>";
			$polygon_shape_string .= "POLYGONSHAPE" . $i . ".setMap(map);<br>shapes.push(POLYGONSHAPE" . $i . ");";
			
			$google_string .= $polygon_shape_string . "<br><br>";
			
			$i++;
		}
		$php_string .= ");";
	}
?>

<html class="no-js" lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="description" content="Convert GeoJSON polyhons to Google Maps Javascript API Shapes">
		<meta name="keywords" content="HTML,CSS,XML,JavaScript,GeoJSON,Google Maps">
		<meta name="author" content="Spookytheboy">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<meta name="twitter:card" content="Convert GeoJSON polygons to Google Maps Javascript API Shapes">
		<meta name="twitter:url" content="http://spooky.codes/geojsonconverter">
		<meta name="twitter:title" content="Indianapolis Web Developer">
		<meta name="twitter:description" content="Web developer and Screen Printer living in Indianapolis.">
		<meta name="twitter:image" content="http://spooky.codes/images/misc/twitterimg2.jpeg">

		<title>GeoJson Conversion Tool - spooky.codes</title>
		
		<link rel="shortcut icon" type="image/png" href="https://spooky.codes/favicon.ico">
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<link rel="stylesheet/less" href="https://spooky.codes/GeoJSONConverter/css/bootstrap.min.less">
		<script src="https://spooky.codes/GeoJSONConverter/js/bootstrap.min.js"></script>

		<link rel="stylesheet" href="https://spooky.codes/GeoJSONConverter/geoconverter/styles/monokai_sublime.css">
		<link rel="stylesheet" href="https://spooky.codes/GeoJSONConverter/css/styles.css">
		<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
		<script src="https://spooky.codes/GeoJSONConverter/geoconverter/highlight.pack.js"></script>
		<script src="https://spooky.codes/GeoJSONConverter/js/geo-scripts.js"></script>
		<script>hljs.initHighlightingOnLoad();</script>
	</head>
	<body>
		<div class="global-wrapper">
			<div class="container">
				<div class="col-xs-12">
					<h1>GeoJSON Conversion Tool</h1>
					<p style="padding-bottom: 0px;">
						Convert GeoJSON data into useable strings for <a href="https://developers.google.com/maps/documentation/javascript/examples/polygon-arrays" target="_blank">Google Maps Javascript API</a> mapping tool <i>or</i> a PHP validation library.
					</p>
					<p>
						1. Open a .KML or .KMZ file from Google Maps to <a href="http://www.geojson.io" target="_blank">geojson.io</a><br>
						2. Paste output below and convert
					</p>
					<div class="geoJSON-form">
						<form action="" method="POST">
							<div class="form-group">
								<label for="text">Paste GeoJSON here:</label>
								<textarea class="form-control json-input" name="geojson-input" style="width: 100%; height: 500px;"></textarea>
							</div>
							<br>
							<button type="submit" class="button button-default">Convert</button>
						</form>
					</div>
				</div>
			</div>

			<div class="container" style="margin-top: 30px;">
				<div class="row">
					<div class="col-xs-12">
						Google Maps API:
						<div class="google-coordinates" style="font-family: monospace;">
							<?php
								if(isset($php_string)) {
									// echo "<br><button class='clickToSelectGoogle button-default button-sm'>Select All</button><br>";
									echo "<pre style='background-color: #23241f;'><code id='selectmegoogle' class='php' style='height: 500px;'>" . $google_string . "</code></pre>";
								} else {
									echo "<div style='background-color: #f3d6d6; color: #fff; border-radius: 4px; padding: 5px;'>N/A</div>";
								}
							?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="container" style="margin-top: 30px; margin-bottom: 30px;">
				<div class="row">
					<div class="col-xs-12">
						Formatted PHP Coordinates:
						<div class="php-coordinates" style="font-family: monospace;">
							<?php
								if(isset($php_string)) {
									// echo "<br><button class='clickToSelect button-default button-sm'>Select All</button><br>";
									echo "<pre style='background-color: #23241f;'><code id='selectme' class='php' style='height: 500px;'>" . $php_string . "</code></pre>";
								} else {
									echo "<div style='background-color: #f3d6d6; color: #fff; border-radius: 4px; padding: 5px;'>N/A</div>";
								}
							?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="container container-footer" style="margin-top: 30px; margin-bottom: 30px;">
				<div class="row">
					<div class="col-xs-12">
						Craig Bavender &copy; <?php echo date("Y"); ?> – <a href="https://spooky.codes">https://spooky.codes</a>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>