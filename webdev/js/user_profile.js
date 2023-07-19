
var userImgInput = document.getElementById('imageInput');
var imgContainer = document.getElementById('imgContainer');

userImgInput.onchange = function(){
    var userImg = document.createElement("img");

    while (imgContainer.firstChild) {
        imgContainer.removeChild(imgContainer.firstChild);
    }
    if (userImgInput.files.length > 0){

        const reader = new FileReader();

        reader.addEventListener('load', ()=>{
            userImg.classList.add("userImage");
            userImg.setAttribute("src",reader.result); 
        })
        imgContainer.append(userImg);
        reader.readAsDataURL(this.files[0]);
    }
}

var editBtn = document.getElementById('editBtn');
var cancelBtn = document.getElementById('cancelBtn');
var saveBtn = document.getElementById('saveBtn');
var choosePht = document.getElementById('choosePht');
var edit_password = document.getElementById('edit_password');


var old_password_div = document.getElementById('old_password_div');
var new_password_div = document.getElementById('new_password_div');

editBtn.onclick = function(){
    saveBtn.style.display="inline-block";
    cancelBtn.style.display="inline-block";
    editBtn.style.display="none";
    choosePht.style.display="inline-block";
    document.getElementById('name').disabled = false;
    document.getElementById('phone').disabled = false;
    document.getElementById('mail').disabled = false;


    edit_password.style.display="inline-block";


}

cancelBtn.onclick = function(){
    saveBtn.style.display="none";
    cancelBtn.style.display="none";
    editBtn.style.display="inline-block";
    choosePht.style.display="none";
    document.getElementById('name').disabled = true;
    document.getElementById('phone').disabled = true;
    document.getElementById('mail').disabled = true;

    edit_password.style.display="none";
    document.getElementById('oldpassword').disabled = true;
    document.getElementById('newpassword').disabled = true;
    old_password_div.className = 'information hidden';
    new_password_div.className = 'information hidden';
}

edit_password.onclick = function(){
    edit_password.style.display="none";
    old_password_div.className = 'information';
    new_password_div.className = 'information';
    document.getElementById('oldpassword').disabled = false;
    document.getElementById('newpassword').disabled = false;
}

function myFunction() {
    var x =document.getElementById("imageInput").required;
    // document.getElementById("demo").innerHTML = x;
}

window.addEventListener('load',myFunction,false);

submitForms = function(){
    document.getElementById("imgform").submit();
    document.getElementById("form").submit();
}