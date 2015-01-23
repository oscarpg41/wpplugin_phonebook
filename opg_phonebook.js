jQuery(document).ready(function() {

	jQuery('.btnDeletePhone').click(function() {
		var url = "admin.php?page=opg_phonebook&task=remove_phone&id=" + this.id;
	    var r = confirm("Está seguro de eliminar este registro?");
	    if (r == true) {
			window.location = url; 
	    }
	});
});