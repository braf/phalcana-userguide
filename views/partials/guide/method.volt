

<div class="method">

    {% set declares = method.method.getDeclaringClass() %}
    <div class="method-head" id="{{ method.method.name }}">
        {{ method.modifiers}} <strong>{{ method.method.name }}(</strong><span class="data-type">{{ method.params ? method.paramsShort() : '' }}</span><strong>)</strong>
        <small>(defined in {{ userguide.classLink(declares.name, method.method.name ) }})</small>
    </div>
    <div class="inner">
        <div class="description">
            {{ method.description() }}
        </div>

        {% if method.tags|length %}
        <div class="tags">
            <strong>Tags</strong>
            <ul>
                {% for name, tset in method.tags %}
                    {% set links = userguide.classLinks(tset) %}
                    <li><small>{{ name|ucfirst }}</small>{{ links ? ' - ' ~ implode(', ', links) : '' }}

                {% endfor %}
            </ul>
        </div>
        {% endif %}

        {% if method.params %}
            <div class="params">
                <strong>Parameters</strong>
                <ul>
                {% for param in method.params %}
                    <li>
                        <code>{{ param.reference ? 'byref ' : '' }}{{ param.type ? userguide.classLink(param.type) : 'unknown' }}</code>
                        <strong>${{ param.name }}</strong>
                        {{ param.default ? '<small> = ' ~ param.default ~ '</small>' : '<small>required</small>'  }}
                        {{ param.description ? ' - ' ~ param.description : '' }}
                    </li>
                    {% endfor %}
                </ul>
            </div>
        {% endif %}

        {% if method.returns() %}
        <div class="returns">
            <strong>Return Values</strong>
            <ul class="return">
                {% for rset in method.returns() %}
                    <li><code>{{ userguide.classLink(rset[0]) }}</code>{% if rset[1] %} - {{ rset[1]|ucfirst }}{% endif %}</li>
                {% endfor %}
            </ul>
        </div>
        {% endif %}

        {% if method.source %}
            <div class="method-source">
                <strong>Source Code</strong>
                <pre class="brush: php">{{ method.source }}</pre>
            </div>
        {% endif %}
    </div>
</div>
