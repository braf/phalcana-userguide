<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />

        <title>{{ title.get() }}</title>

        <meta http-equiv="description" content="">
        <link rel="shortcut icon" type="image/x-icon" href="{{ url('public/favicon.ico') }}">

        {{ assets.outputCss('cssHeader') }}
        {{ assets.outputJs('jsHeader') }}

        <script>
            var url_base = '{{ url('') }}';
        </script>

    </head>
    <body>


        {{ content() }}


        {{ assets.outputCss('cssFooter') }}
        {{ assets.outputJs('jsFooter') }}



        {% if setup.google_analytics is defined %}
            <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                ga('create', '{{ setup.google_analytics }}', 'auto');
                ga('send', 'pageview');



            </script>
        {% endif %}

    </body>
</html>
