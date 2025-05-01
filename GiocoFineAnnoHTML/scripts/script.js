"use strict";

let statoGioco=false;
let imageTalpa=document.createElement("img");
imageTalpa.setAttribute("src", "./src/images/talpa.png");
imageTalpa.setAttribute("onmousedown", "hittedMole(event)");
imageTalpa.setAttribute("draggable", "false");

//variabili statistiche
let nRecord=1;
let punteggio=0;
let tempo=0;

let oldNum=0;
function posizionaTalpa(){
    let buchi=document.querySelectorAll(".hole");
    let num=Math.floor(Math.random()*(buchi.length));
    while(oldNum===num){
        num=Math.floor(Math.random()*(buchi.length));
        //controlliamo se nella posizione scelta ci siano dei strumenti
        let strumento=document.getElementById("strumento");
        if(strumento!=null && buchi[num].contains(strumento)){
            let tipo="strumento.src";
            if(tipo.indexOf("martello")!=-1){

            }else if(tipo.indexOf("bomba")!=-1){
                //se la posizione ha la bomba inseriamo una esplosione e aumentiamo 5 punti
                let statistiche=document.querySelectorAll("section p span");
                punteggio+=5;   
                statistiche[1].innerText=punteggio;
            }else if(tipo.indexOf("lucchetto")!=-1){
                //se la posizione è bloccata dal lucchetto riposizioniamo la talpa
                num=Math.floor(Math.random()*(buchi.length));
            }
        }
    }
    buchi[num].appendChild(imageTalpa);
    oldNum=num;
}
let intervalloTalpa;
let intervalloTempoStatistica;
function changeGameState(){
    let bPause=document.getElementById("pauseButton");
    let bStatus=document.querySelector("nav button");
    if(!statoGioco && bPause.innerText != "Play"){
        bPause.style.display="inline";
        bStatus.innerText="Stop";
        statoGioco=true;
        document.getElementById("table").style.cursor="url('./src/cursors/martelloAlto.cur'), auto";
        changeButtonStatus(true);
        posizionaTalpa(); //facciamo comparire subito la talpa
        intervalloTalpa=window.setInterval(posizionaTalpa, 500);
        intervalloTempoStatistica=window.setInterval(tempoStatistica, 1000);
    }else {
        bPause.style.display="none";
        document.getElementById("table").style.cursor="";
        bPause.innerText="Pause";
        bStatus.innerText="Start";
        statoGioco=false;
        window.clearInterval(intervalloTalpa);
        window.clearInterval(intervalloTempoStatistica);
        aggiungiRecord();
        ripulisciTable();
        changeButtonStatus(false);
        let statistiche=document.querySelectorAll("section p span");
        statistiche[0].innerText="0:00";
        statistiche[1].innerText=0;
    }
}
function hittedMole(event){
    if(statoGioco){
        // pos 0=time 1=punti
        let statistiche=document.querySelectorAll("section p span");
        punteggio+=1;
        statistiche[1].innerText=punteggio;
        let padre=event.target.parentElement;
        padre.removeChild(imageTalpa);
        let talpaColpita=document.createElement("img");
        talpaColpita.src="./src/images/talpaColpita.png";
        talpaColpita.setAttribute("draggable", "false");
        padre.appendChild(talpaColpita)
        window.setTimeout(e=>{
            if(padre.contains(talpaColpita)){
                padre.removeChild(talpaColpita)
            }
        }, 500);
    }
    console.log("colpita");
}
function tempoStatistica(){
    if(statoGioco){
        tempo++;
        let minuti=Math.floor(tempo/60);
        let secondi=tempo-minuti*60
        console.log(`${minuti}:${secondi<10?"0":""}${secondi}`);
        let statistiche=document.querySelectorAll("section p span");
        statistiche[0].innerText=`${minuti}:${secondi<10?"0":""}${secondi}`;
        if(secondi===5){
            let strumento=document.getElementById("strumento");
            console.log(strumento);
            if(strumento!=null){
                strumento.parentElement.removeChild(strumento);
            }
        }
    }
}
function aggiungiRecord(){
    let table=document.querySelector("table");
    let riga=document.createElement("tr");
    let minuti=Math.floor(tempo/60);
    let secondi=tempo-minuti*60
    riga.innerHTML=`<tr><th>${nRecord}</th><td>${minuti}:${(secondi<10?"0":"")+secondi}</td><td>${punteggio}</td></tr>`
    nRecord++;
    punteggio=0;
    tempo=0;
    table.appendChild(riga);
}
/**
 * abbassiamo il martello per 200mS
 */
function lowerAmmer(){
    if(statoGioco){
        document.getElementById("table").style.cursor="url('./src/cursors/martelloBasso.cur'), auto";
        window.setTimeout(e=>{document.getElementById("table").style.cursor="url('./src/cursors/martelloAlto.cur'), auto";},150)
    }
}
function pauseOrPlay(evento){
    let button=evento.target;
    if(statoGioco){
        statoGioco=false;
        button.innerText="Play";
        window.clearInterval(intervalloTalpa);
    }else{
        statoGioco=true;
        button.innerText="Pause";
        intervalloTalpa=window.setInterval(posizionaTalpa, 500);
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
    //controllo che cursore è selezionato ed aggiungo l'immagine al div
    let img=document.createElement('img');
    img.id="strumento";
    switch (document.body.style.cursor.toString()){
        case 'url("./src/cursors/bomba.cur"), auto': 
            img.setAttribute("src", "./src/images/bomba.png");
            img.setAttribute("onclick", "rimuoviStrumentoClick(event)");
            div.appendChild(img);
            changeButtonStatus(true);
            break;
        case 'url("./src/cursors/lucchetto.cur"), auto': 
            img.setAttribute("src", "./src/images/lucchetto.png");
            img.setAttribute("onclick", "rimuoviStrumentoClick(event)");
            div.appendChild(img);
            changeButtonStatus(true);
            break;
        case 'url("./src/cursors/martello.cur"), auto': 
            img.setAttribute("src", "./src/images/martello.png");
            img.setAttribute("onclick", "rimuoviStrumentoClick(event)");
            div.appendChild(img);
            changeButtonStatus(true);
            break;
    }
    document.body.style.cursor="default";
}
function rimuoviStrumentoClick(event){
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
    document.getElementById("viewImpostazioni").style.display="block";
}