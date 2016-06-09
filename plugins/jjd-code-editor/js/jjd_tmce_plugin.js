(function() {
    tinymce.PluginManager.add('jjd_code_button', function( editor, url ) {
        editor.addButton( 'jjd_code_button', {
            icon: 'jjd_code_button',
            onclick: function() {
            var selected = tinyMCE.activeEditor.selection.getContent();
            var content = '<pre><code>' + selected + '</code></pre>';
            editor.insertContent( content + "\n" );
            }
        });
    });
})();