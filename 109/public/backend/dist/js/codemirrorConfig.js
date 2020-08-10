var editor = CodeMirror.fromTextArea(document.getElementById("css"), {
	extraKeys: {"Ctrl-Space": "autocomplete"},
	lineNumbers: true,
	matchBrackets: true,
	mode: "text/css",
	indentUnit: 4,
	indentWithTabs: true,
	viewportMargin: Infinity
});
editor.setSize('100%', 470);