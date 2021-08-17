<header>
	<h1>
		<a href="{{route('frontend_dashboard')}}">{{config('frontend.title')}}</a>
	</h1>

	<a href="#" id="menu_button"><div></div></a>
	<a href="#" id="filter_button" class="{{$configType === 'model' ? '' : 'hidden'}}"><div></div></a>

	<div id="mobile_menu_wrapper">
		<ul id="mobile_menu">
			@foreach ($menu as $key => $item)
				@include('frontend::partials.menu_item')
			@endforeach
		</ul>
	</div>

	<ul id="menu">
		@foreach ($menu as $key => $item)
			@include('frontend::partials.menu_item')
		@endforeach
	</ul>
	<div id="right_nav">
		@if (count(config('frontend.locales')) > 0)
			<ul id="lang_menu">
				<li class="menu">
				<span>{{config('app.locale')}}</span>
					@if (count(config('frontend.locales')) > 1)
						<ul>
							@foreach (config('frontend.locales') as $lang)
								@if (config('app.locale') != $lang)
									<li>
										<a href="{{route('frontend_switch_locale', array($lang))}}">{{$lang}}</a>
									</li>
								@endif
							@endforeach
						</ul>
					@endif
				</li>
			</ul>
		@endif
		<a href="{{url(config('frontend.back_to_site_path', '/'))}}" id="back_to_site">{{trans('frontend::frontend.backtosite')}}</a>
		@if(config('frontend.logout_path'))
			<a href="{{url(config('frontend.logout_path'))}}" id="logout">{{trans('frontend::frontend.logout')}}</a>
		@endif
	</div>
</header>