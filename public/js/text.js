function iFrameOn(){
	richTextField.document.designMode = 'On';
}
function iBold(){
	richTextField.document.execCommand('bold',false,null); 
}
function iUnderline(){
	richTextField.document.execCommand('underline',false,null);
}
function iItalic(){
	richTextField.document.execCommand('italic',false,null); 
}
function iUnorderedList(){
	richTextField.document.execCommand("InsertOrderedList", false,"newOL");
}
function iOrderedList(){
	richTextField.document.execCommand("InsertUnorderedList", false,"newUL");
}
function submit_form(){
	console.log(tinymce.get('rich').getContent({format : 'raw'}));
	var theForm = document.getElementById("myform");
	theForm.elements["myTextArea"].value = tinymce.get('rich').getContent({format : 'raw'});
	theForm.submit();
}