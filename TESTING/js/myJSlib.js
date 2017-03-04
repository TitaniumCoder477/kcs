function deleteRow(element)
{
	var tagName = element.tagName;
	if(tagName == "TR") {
		element.parentNode.deleteRow(element.sectionRowIndex);
		return;
	}
	
	deleteRow(element.parentNode)
}

function incrementElementID(element, incrementVal) {
	
	if(element.hasAttribute("id")) {
		var idVal = element.getAttribute("id");
		element.setAttribute("id",idVal+incrementVal);
	}
	
	var numChildren = element.childElementCount;
	for (var i=0; i<numChildren; i++)
		incrementElementID(element.children[i],incrementVal);
		
	return;
}

function incrementElementName(element, incrementVal) {
	
	if(element.hasAttribute("name")) {
		var nameVal = element.getAttribute("name");
		element.setAttribute("name",nameVal+incrementVal);
	}
	
	var numChildren = element.childElementCount;
	for (var i=0; i<numChildren; i++)
		incrementElementName(element.children[i],incrementVal);
		
	return;
}

function insRow(tableId)
{
	var table, numRows, newRow, len;
	try {
		table = document.getElementById(tableId);
		numRows = table.rows.length;
		// deep clone the first row, assuming row 0 is a header
		newRow = table.rows[1].cloneNode(true);
		// get the total number of rows
		numRows = table.rows.length;
	} catch (err) {
		txt="There was an error on this page.\n\n";
		txt+="Error description: " + err.message + "\n\n";
		txt+="Click OK to continue.\n\n";
		alert(txt);
	}
	
	try {
		incrementElementID(newRow,numRows-1);
		incrementElementName(newRow,numRows-1);
		// append the new row to the table
		table.getElementsByTagName("tbody")[0].appendChild(newRow);
	} catch (err) {
		txt="There was an error on this page.\n\n";
		txt+="Error description: " + err.message + "\n\n";
		txt+="Click OK to continue.\n\n";
		alert(txt);
	}
}

function insRow(tableId, newRowParams)
{
	var table, numRows, newRow, len;
	try {
		table = document.getElementById(tableId);
		numRows = table.rows.length;
		// deep clone the first row, assuming row 0 is a header
		newRow = table.rows[1].cloneNode(true);
		// get the total number of rows
		numRows = table.rows.length;
	} catch (err) {
		txt="There was an error on this page.\n\n";
		txt+="Error description: " + err.message + "\n\n";
		txt+="Click OK to continue.\n\n";
		alert(txt);
	}
	
	try {
		incrementElementID(newRow,numRows-1);
		incrementElementName(newRow,numRows-1);
		// append the new row to the table
		table.getElementsByTagName("tbody")[0].appendChild(newRow);
	} catch (err) {
		txt="There was an error on this page.\n\n";
		txt+="Error description: " + err.message + "\n\n";
		txt+="Click OK to continue.\n\n";
		alert(txt);
	}
}
	
function redirectPageOnCountdown(prefix, seconds, postfix, display, page) {
	setInterval(function () {
		display.textContent = prefix + seconds + postfix;

		if (--seconds < 0) {
			window.location=page;
		}
	}, 1000);
}

function submitForm(form) {
	document.getElementById(form).submit();
}

function loadPage(page) {
	document.location.href=page;
}

/** When an input is clicked, erase the contents so that the list works */
function onClickInput(id) {
	var input = document.getElementById(id+"/INPUT");
	var td = document.getElementById(id+"/TD");
	input.value = "";
}

/** If an input has nothing in it, bring back the previous tag */
function onLeaveInput(id,id_key) {
	var input = document.getElementById(id+"/INPUT");
	var input_key = document.getElementById(id_key+"/INPUT");
	if(input.value.length == 0)
		input.value = input_key.value;
	validateInput(id);
}

/** If the provided input item is empty, make it red */
function validateInput(id) {
	var input = document.getElementById(id+"/INPUT");
	var td = document.getElementById(id+"/TD");
	var result = (input.value.length != 0);
	td.style.backgroundColor = (result ? "white" : "red");		
	return result;
}