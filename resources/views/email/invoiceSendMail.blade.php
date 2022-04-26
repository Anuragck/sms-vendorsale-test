
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SMS</title>
</head>
<body>
<h1> Hello {{$details['customer_name']}}</h1>


<h3> Your Invoice link : <span><a target="_self" href="{{$details['link']}}" > {{$details['link']}} </a>  </span></h3>


<h4>Please go through this link to download your invoice.</h4>
<h3> {{$details['message']}}  </h3>

</body>
</html>
