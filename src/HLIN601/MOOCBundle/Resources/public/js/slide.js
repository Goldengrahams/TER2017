/* Set the width of the side navigation to 250px */
function openNav(matiere) {
	var coll = document.getElementsByClassName("sidenav");
	for(var i=0, len=coll.length; i<len; i++){
        coll[i].style.width = "0";
    }
    document.getElementById(matiere).style.width = "700px";
}

/* Set the width of the side navigation to 0 */
function closeNav(matiere) {
    document.getElementById(matiere).style.width = "0";
}