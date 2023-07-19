//Preven form submit if the information not entered
let saveBtn = document.getElementById('saveBtn');


//Display Uploaded Image
let fileInput = document.getElementById("file-input");
let imageContainer = document.getElementById("images");
// let numOfFiles = document.getElementById("num-of-files");

fileInput.onchange = function(){
    imageContainer.innerHTML = "";
    // numOfFiles.textContent = `${fileInput.files.length} Files Selected`;

    for(i of fileInput.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");
        figure.classList.add("figureUploaded");
        figCap.classList.add("figcaptionUploaded");
        figCap.innerText = i.name;
        figure.appendChild(figCap);
        reader.onload =()=>{
            let img = document.createElement("img");
            img.classList.add("imgUploaded");
            img.setAttribute("src",reader.result); 
            figure.insertBefore(img,figCap);
        }
        imageContainer.append(figure);
        reader.readAsDataURL(i);
    }
}

let deleteCheckBox = document.getElementById("deleteImage");
deleteCheckBox.onclick = function(){

  imageContainer.innerHTML = "";
  deleteCheckBox.checked = true;
}


let timer;

document.addEventListener('input', e => {
  const el = e.target;
  
  if( el.matches('[data-color]') ) {
    clearTimeout(timer);
    timer = setTimeout(() => {
      document.documentElement.style.setProperty(`--color-${el.dataset.color}`, el.value);
    }, 100)
  }
})