<!DOCTYPE html>
<html>
<head>
	<title>Error Page - Shopify</title>
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
  <script type="text/javascript">
      ShopifyApp.init({
        apiKey: '{{env("SHOPIFY_APIKEY")}}',
        shopOrigin: 'https://{{$shop}}'
      });
  </script>
	<!------ Include the above in your HEAD tag ---------->
	<style type="text/css">
		  .center {text-align: center; margin-left: auto; margin-right: auto; margin-bottom: auto; margin-top: auto;}

	</style>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="span12">
      <div class="hero-unit center">
          <h1>Ohh!!!!<small><font face="Tahoma" color="red">Error</font></small></h1>
          <br />
          <p>{{$msg}}</p>
          <a href="{{route('app.auth')}}/?shop={{$shop}}" class="btn btn-large btn-info"><i class="icon-home icon-white"></i> Take Me Home</a>
        </div>
     
        <br />
        <p></p>
        <!-- By ConnerT HTML & CSS Enthusiast -->  
    </div>
  </div>
</div>

</body>
</html>