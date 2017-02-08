// Adds the WYSIWYG editor to the textarea
function init() {
  // Search any selected nicedit checkbox
  jQuery('input:checkbox.nicedit:checked').each(function() {
    var $this = jQuery(this);
    // Remove previous nicedit
    var niceditor = $this.data('nicedit');
    var $textarea = $this.parents('form:first').find('textarea');
    if (niceditor) niceditor.removeInstance($textarea.attr('id'));
console.log($textarea);    
    // Configure nicedit buttons
    var area = new nicEditor({buttonList : ['bold','italic', 'link', 'unlink', 'xhtml']}).panelInstance($textarea.attr('id'));
    $this.data('nicedit', area);
  });
}

jQuery(document).ready(function() {
  // Toggle nicedit when checkbox is clicked
  // Store nicedit object if needed
  jQuery('.widget-liquid-right').on('click', 'input:checkbox.nicedit', function() {
    var $this = jQuery(this);
    var $textarea = $this.parents('form:first').find('textarea');
    var niceditor = $this.data('nicedit');
    if (niceditor) {
      niceditor.removeInstance($textarea.attr('id'));
      $this.data('nicedit', false);
    } else {
      var area = new nicEditor({buttonList : ['bold','italic', 'link', 'unlink', 'xhtml'], iconsPath: admin.path+ '/js/nicEditorIcons.gif'}).panelInstance($textarea.attr('id'));
      $this.data('nicedit', area);
    }
  });

  // Before widget submit it's necessary to update nicedit content 
  jQuery('#widgets-right').on('click', ':submit', function() {
    for(var i=0; i<nicEditors.editors.length; i++) for(var j=0; j<nicEditors.editors[i].nicInstances.length; j++) nicEditors.editors[i].nicInstances[j].saveContent();
  });

  // Caputre widgets saveOrder function for adding init function
  wpWidgets._saveOrder = wpWidgets.saveOrder
  wpWidgets.saveOrder = function() {
    init();
    wpWidgets._saveOrder();
  }
  
  // Activate plugin
  init();
});