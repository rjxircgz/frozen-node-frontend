<!DOCTYPE html>
<html lang="<?php echo config('application.language') ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<title>{{ config('frontend.title') }}</title>

	@foreach ($css as $url)
		<link href="{{$url}}" media="all" type="text/css" rel="stylesheet">
	@endforeach

	<!--[if lte IE 9]>
		<link href="{{asset('packages/frozennode/frontend/css/browsers/lte-ie9.css')}}" media="all" type="text/css" rel="stylesheet">
	<![endif]-->

</head>
<body>
	<div id="wrapper">
		@include('frontend::partials.header')

		{!! $content !!}

		@include('frontend::partials.footer')
	</div>

	@foreach ($js as $url)
		<script src="{{$url}}"></script>
	@endforeach
</body>
</html>