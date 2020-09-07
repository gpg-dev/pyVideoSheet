<!DOCTYPE html>
<html lang="en">
  <head>    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">                
		<link rel="stylesheet" href="{{URL::asset('backends/bootstrap/css/bootstrap.min.css')}}">
		<link rel="stylesheet" href="{{URL::asset('backends/css/login.css')}}">
  </head>
  <body>    
    <div class="wrap">    
      <div class="container">              
          @yield('content')
      </div>
    </div>    
    <script src="{{URL::asset('backends/plugins/jQuery/jQuery-2.1.4.min.js')}}"></script>    
  </body>
</html>

