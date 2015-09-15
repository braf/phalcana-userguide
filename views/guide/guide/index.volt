<h3>User Guide</h3>

<p>The following modules have userguide pages.</p>

{% for key, value in menu %}
    <div>
        {% if value.enabled %}
            <a href="{{ url('guide/' ~ key) }}">{{ value.name }}</a> &ndash; {{ value.description }}
        {% endif %}
    </div>
{% endfor %}

<!-- <pre><div class="line"><span class="number">1</span><span class="code">code here</span></div><div class="line"><span class="number">2</span><span class="code">code here</span></div></pre> -->
