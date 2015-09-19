
@import '../../components/jquery/dist/jquery.js';
@import '../../components/foundation/js/foundation.js';

@import '../../components/SyntaxHighlighter/scripts/XRegExp.js';
@import '../../components/SyntaxHighlighter/scripts/shCore.js';
@import '../../components/SyntaxHighlighter/scripts/shBrushPhp.js';



$(document).ready(function () {

    $(document).foundation();

    SyntaxHighlighter.defaults['toolbar'] = false;
    SyntaxHighlighter.defaults['gutter'] = false;

    SyntaxHighlighter.all();
});
