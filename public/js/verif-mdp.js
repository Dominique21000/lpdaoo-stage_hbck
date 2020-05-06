function checkPass()
{
    var pass1 = document.getElementById("mdp1").value;
    var pass2 = document.getElementById("mdp2").value;

    if (pass1 == pass2){
        return true;
    }
    else{
        alert("Les deux mots de passe doivent être identiques");
        return false;
    }
}