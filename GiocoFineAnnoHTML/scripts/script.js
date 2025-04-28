"use strict";

let statoGioco=false;
let imageTalpa=document.createElement("img");
imageTalpa.setAttribute("src", "./src/images/talpa.png");

let oldNum=0;
function posizionaTalpa(){
    let buchi=document.querySelectorAll(".hole");
    let num=Math.floor(Math.random()*(buchi.length));
    while(oldNum===num){
        num=Math.floor(Math.random()*(buchi.length));
    }
    buchi[num].appendChild(imageTalpa);
    console.log("talpa posizionata");
    oldNum=num;
}
let intervallo;
function changeGameState(){
    let bPause=document.getElementById("pauseButton");
    let bStatus=document.querySelector("nav button");
    if(!statoGioco){
        bPause.style.display="inline";
        bStatus.innerText="Stop";
        statoGioco=true;
        changeButtonStatus(true);
        intervallo=setInterval(posizionaTalpa, 500);
    }else{
        bPause.style.display="none";
        bStatus.innerText="Start";
        statoGioco=false;
        clearInterval(intervallo);
        ripulisciTable();
        changeButtonStatus(false);
    }
}

function changeButtonStatus(stato){
    let pulsanti=document.querySelectorAll("aside button")
    if(stato){
        //disabilitiamo i pulsanti
        pulsanti.forEach( elemento => {
                elemento.disabled=true;
            }
        )
    }else{
        //abilitiamo i pulsanti
        pulsanti.forEach( elemento => {
                elemento.disabled=false;
            }
        )
    }
}

function selectItems(elemento){
    document.body.style.cursor = "url('./src/cursors/"+elemento+"'), auto";
}

function posiziona(evento){
    let div=evento.target;
    console.log(document.body.style.cursor.toString())
    //controllo che cursore è selezionato ed aggiungo l'immagine al div
    let img=document.createElement('img');
    switch (document.body.style.cursor.toString()){
        case 'url("./src/cursors/bomba.cur"), auto': 
            console.log("posizionato bomba");
            img.setAttribute("src", "./src/images/bomba.png");
            img.setAttribute("onclick", "rimuoviStrumento(event)");
            div.appendChild(img);
            changeButtonStatus(true);
            break;
        case 'url("./src/cursors/lucchetto.cur"), auto': 
            console.log("posizionato lucchetto");
            img.setAttribute("src", "./src/images/lucchetto.png");
            img.setAttribute("onclick", "rimuoviStrumento(event)");
            div.appendChild(img);
            changeButtonStatus(true);
            break;
        case 'url("./src/cursors/martello.cur"), auto': 
            console.log("posizionato martello");
            img.setAttribute("src", "./src/images/martello.png");
            img.setAttribute("onclick", "rimuoviStrumento(event)");
            div.appendChild(img);
            changeButtonStatus(true);
            break;
        default:
            console.log("non corrisponde");
    }
    document.body.style.cursor="default";
}
function rimuoviStrumento(event){
    if (!statoGioco) {
        let strumento=event.target;
        let padre = strumento.parentElement;
        padre.removeChild(strumento);
        changeButtonStatus(false);
    }
}
function ripulisciTable(){
    let padre=document.querySelectorAll(".hole");
    let images=document.querySelectorAll(".hole img");
    console.log(padre.length);
    console.log(images.length);
    padre.forEach(elemento=>{
        images.forEach(image=>{
            if(elemento.contains(image)){
                elemento.removeChild(image);
            }
        });
    });
}

function hideSettings(){
    document.getElementById("viewImpostazioni").style.display="none";
}
function viewSettings(){
    document.getElementById("viewImpostazioni").style.display="";
}