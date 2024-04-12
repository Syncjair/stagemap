function barcodecijfer(number) {
    document.getElementById("barcodeinput").value += number;

}

function leegmaken() {
    document.getElementById("barcodeinput").value = "";
}

function afrekenen() {
    alert("Afrekenen succesvol!"); 
}