
<header>
	<div class="row">
		<div class="column">
			<img src="{{ url('public/guide/img/phalcana.png') }}" alt="Phalcana">

			<div id="nav">
				<a {% if guide %}class="active"{% endif %} href="{{ url('guide') }}">User&nbsp;Guide</a>
				<a {% if not guide %}class="active"{% endif %} href="{{ url('guide/api') }}">API&nbsp;Browser</a>
			</div>
		</div>
	</div>
</header>

<section id="main">
	
	<div class="row">

		<div class="column large-4" id="menu">
			{% if guide %}			
				<h3><a href="{{ url('guide') }}">Modules</a></h3>
				{% if menu is scalar %}
					{{ menu }}
				{% else %}
				{% for key, value in menu %}
					{% if value.enabled %}
						<h5><a href="{{ url('guide/' ~ key) }}">{{ value.name }}</a></h5>
						{% if value.children is defined %}
							<ul>				
								<li><a href="{{ url('guide/' ~ key) }}">{{ value.name }}</a></li>					
							</ul>
						{% endif %}
					{% endif %}
					
				{% endfor %}
				{% endif %}
			{% else %}
				<h3><a href="{{ url('guide/api') }}">API</a></h3>

				{% for key, item in menu %}
					<ul>
						<li><h5>{{ key }}</h5></li>
						<li><ul>
							{% if item['Base'] is defined %}
								{% for k, v in item['Base'] %}
									<li>{{ v }}</li>
								{% endfor %}
							{% endif %}
							{% for k, v in item %}
								{% if k is 'Base' %}
									{% continue %}
								{% endif %}
								<li>{{ k }}
									<ul>
									{% for lk, lv in v %}
										<li>{{ lv }}</li>		
									{% endfor %}
									</ul>
								</li>
							{% endfor %}
						</ul></li>
					</ul>
				{% endfor %}

			{% endif %}
		</div>
		<div class="column large-12">	
			<div class="content">
				{{ content() }}
			</div>

		</div>

	</div>

</section>


<footer>
	<div class="row">
		<div class="column text-right"><p><small>&copy; 2015 Phalcana</small></p></div>
	</div>
	
</footer>