<div class="class-header">
    <div class="class-name">
        <span>{{ doc.modifiers }}</span><span> {{ doc.class.isInterface() ? 'interface' : 'class' }}</span> <h3>{{ doc.name }}</h3>
    </div>
    {% if not doc.class.getNamespaceName() is '' %}
    <div class="namespace">
        <span>namespace</span> <h4>{{ doc.class.getNamespaceName() }}</h4>
    </div>
    {% endif %}

    <div class="parents">
    {% if doc.parents %}
        {% for parent, link in doc.parentLinks() %}
            <div class="parent">
                <span>extends</span>
                {% if link %}
                    <a href="{{ link }}">{{ parent }}</a>
                {% else %}
                    {{ parent }}
                {% endif %}
            </div>
        {% endfor %}
    {% endif %}
    </div>

    {% if doc.interfaceLinks() %}
        <div class="implements">
            <span>implements</span>
        {% for parent, link in doc.interfaceLinks() %}
                {% if link %}
                    <a href="{{ link }}">{{ parent }}</a>{% if not loop.last %}, {% endif %}
                {% else %}
                    {{ parent }}{% if not loop.last %}, {% endif %}
                {% endif %}
        {% endfor %}
        </div>
    {% endif %}

    <div class="description">
        {{ doc.description() }}
    </div>
</div>

<table class="tags">
    {% for tag, tags in doc.tags %}
        {% for value in tags %}
            <tr class="tag">
                <th>{{ tag }}</th>
                <td>{{ value }}</td>
            </tr>
        {% endfor %}
    {% endfor %}
</table>

<p class="note">
    {% if doc.filename() %}
        Class declared in <code>{{ doc.filename() }}</code> on line {{  doc.class.getStartLine() }}.
    {% else %}
        Class is not declared in a file, it is probably an internal PHP class.
    {% endif %}
</p>


<div class="row" id="class-nav">
    <div class="column large-5">
        <h4>Constants</h4>
        <ul>
        {% if doc.class.getConstants()|length %}
            {% for key, value in doc.constants() %}
                <li><a href="#constant:{{ key }}">{{key}}</a></li>
            {% endfor %}
        {% else %}
            <li>None</li>
        {% endif %}
        </ul>
    </div>
    <div class="column large-5">
        <h4>Properties</h4>
        <ul>
        {% if doc.class.getProperties()|length %}
            {% for value in doc.properties() %}
                <li><a href="#property:{{ value.property.name }}">${{ value.property.name }}</a></li>
            {% endfor %}
        {% else %}
            <li>None</li>
        {% endif %}
        </ul>
    </div>
    <div class="column large-5">
        <h4>Methods</h4>
        <ul>
        {% if doc.class.getMethods()|length %}
            {% for value in doc.methods() %}
                <li><a href="#{{ value.method.name }}">{{ value.method.name }}()</a></li>
            {% endfor %}
        {% else %}
            <li>None</li>
        {% endif %}
        </ul>
    </div>
</div>


{% if doc.class.getConstants()|length %}
<div class="constants">
    <h4>Constants</h4>
    <dl>
        {% for key, value in doc.constants() %}
            <dt id="constant:{{ key }}">{{key}} <!-- <a href="#constant:{{ key }}"></a> --></dt>
            <dd>{{ value }}</dd>
        {% endfor %}
    </dl>
</div>
{% endif %}

{% if doc.class.getProperties()|length %}
<div class="properties">
    <h4>Properties</h4>
    <dl>
        {% for prop in doc.properties() %}
            <dt id="property:{{ prop.property.name }}">{{ prop.modifiers }} {% if not prop.type is null %}<code>{{ prop.type }}</code>{% endif %} ${{ prop.property.name }} <!-- <a href="#constant:{{ prop.property.name }}"></a> --></dt>
            <dd>{{ prop.description }}</dd>
            {% if not prop.value is null %}
                <dd>{{ prop.value }}</dd>
            {% endif %}
            {% if prop.default !== prop.value %}
                <dd><small>Default value:</small> {{ prop.default }}</dd>
            {% endif %}
        {% endfor %}
    </dl>
</div>
{% endif %}

{% if doc.class.getMethods()|length %}
<div class="methods">
    <h4>Methods</h4>
    {% for method in doc.methods() %}
        {{ partial('partials/guide/method', ['method': method]) }}
    {% endfor %}
</div>
{% endif %}
