CKEDITOR.plugins.add("myBreak", {
	requires: 'fakeobjects',
	lang : "af,ar,bg,bn,bs,ca,cs,cy,da,de,el,en,en-au,en-ca,en-gb,eo,es,et,eu,fa,fi,fo,fr,fr-ca,gl,gu,he,hi,hr,hu,id,is,it,ja,ka,km,ko,ku,lt,lv,mk,mn,ms,nb,nl,no,pl,pt,pt-br,ro,ru,si,sk,sl,sq,sr,sr-latn,sv,th,tr,ug,uk,vi,zh,zh-cn",
	icons : "myBreak",
	
	onLoad : function() {
		var cssStyles = ["{",
				"background: url(" + CKEDITOR.getUrl(this.path + "cut.gif") + ") no-repeat left center;",
				"clear: both;",
				"width:100%; _width:99.9%;",
				"border-top: #FF0000 1px dotted;",
				"border-bottom: #FF0000 1px dotted;",
				"padding:0;",
				"height: 20px;",
				"cursor: default;",
			"}"
		].join("").replace(/;/g, " !important;"); // Increase specificity to override other styles, e.g. block outline.

		// Add the style that renders our placeholder.
		CKEDITOR.addCss("div.myBreak" + cssStyles);
	},

	init : function(editor){
		if(editor.blockless)
			return;

		// Register the command.
		editor.addCommand("myBreak", CKEDITOR.plugins.myBreakCmd);

		// Register the toolbar button.
		editor.ui.addButton && editor.ui.addButton("myBreak", {
			label : "Вставить разрыв страницы",
			command : "myBreak",
			toolbar : "paragraph"
		});

		// Opera needs help to select the page-break.
		CKEDITOR.env.opera && editor.on("contentDom", function(){
			editor.document.on("click", function(evt){
				var target = evt.data.getTarget();
				if(target.is("div") && target.hasClass("myBreak"))
					editor.getSelection().selectElement(target);
			});
		});
	}
});

// TODO Much probably there's no need to expose this object as public object.

CKEDITOR.plugins.myBreakCmd = {
	exec : function(editor){
		// Create read-only element that represents a print break.
		var pagebreak = CKEDITOR.dom.element.createFromHtml("<div class=\"myBreak\"></div>", editor.document);
	editor.insertElement(pagebreak);
	}
};