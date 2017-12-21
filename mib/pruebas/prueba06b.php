<?php
?>
<!-- (6) Para hacer dinámico la interacción con el árbol MIB se hará uso de formularios padre/hijo-->
<!-- updateParent es una función que actualiza los valores en el padre.-->
<!-- A partir de la propiedad opener toma campos/valores del padre, mismo que se asginan a través del hijo-->
<!-- Se cierra la ventana. -->
<SCRIPT LANGUAGE="JavaScript">
function updateParent() {
    opener.document.parentForm.pf1.value = document.childForm.cf1.value;
    self.close();
    return false;
}
</SCRIPT>

<!-- Se activa updateParent al dar click en el botón.-->
<FORM NAME="childForm" onSubmit="return updateParent();">
<BR><INPUT NAME="cf1" TYPE="TEXT" VALUE="">
<BR><INPUT TYPE="SUBMIT" VALUE="Update parent">
</FORM>
