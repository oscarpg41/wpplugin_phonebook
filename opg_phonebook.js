function borrar(id){
	var url = "admin.php?page=opg_phonebook&task=remove_phone&id=" + id;
    var r = confirm("Está seguro de eliminar este registro?");
    if (r == true) {
		window.location = url; 
    }
}