"use strict";

let statoGioco=false;
let imageTalpa=document.createElement("img");
let widthTalpa;
imageTalpa.setAttribute("src", "./src/images/talpa.png");
imageTalpa.setAttribute("onmousedown", "hittedMole(event)");
imageTalpa.addEventListener("touchstart", e=>hittedMole(e));
imageTalpa.setAttribute("draggable", "false");


let effEsplosione=document.createElement("img");
effEsplosione.setAttribute("src", "./src/video/esplosione.gif");

//variabili statistiche
let nRecord=1;
let punteggio=0;
let tempo=0;
let volume=0.5;
let difficolta=1;

let audioMartello;
let audioBomba;
function init(){
    audioMartello=document.getElementById("audioMartello");
    audioBomba=document.getElementById("audioBomba");
    resize();
    updateDifficolta();
    updateVolume();
}

function resize(){
    let buchi=document.getElementsByClassName("hole");
    widthTalpa=window.getComputedStyle(buchi[0]).width;
    document.querySelectorAll(".hole img").forEach(elemento=>{
        elemento.style.width=widthTalpa;
        }   
    );
}

let oldNum=0;
function posizionaTalpa(){
    let buchi=document.querySelectorAll(".hole");
    let num=Math.floor(Math.random()*(buchi.length));
    //prendiamo le variabili utili a capire che strumento è
    let strumento=document.getElementById("strumento");
    //se lo strumento è posizionato nella table affidiamo l'src alla variabile
    let tipo=strumento!=null ? strumento.src : "";
    //se la posizione è la stessa della precedente oppure contiene il lucchetto cambia posizione
    while(oldNum===num || (buchi[num].contains(strumento) && tipo.indexOf("lucchetto")!=-1)){
        num=Math.floor(Math.random()*(buchi.length));
    }
    imageTalpa.style.width=widthTalpa;
    buchi[num].appendChild(imageTalpa);
    oldNum=num;
    //controlliamo se nella posizione scelta ci siano dei strumenti
    if(buchi[num].contains(strumento)){
        console.log("verifica strumento");
        if(tipo.indexOf("martello")!=-1){
            if(!(audioMartello.paused)){
                audioMartello.currentTime=0;
            }
            audioMartello.play();
            let statistiche=document.querySelectorAll("section p span");
            punteggio+=1;   
            statistiche[1].innerText=punteggio;
        }else if(tipo.indexOf("bomba")!=-1){
            //se la posizione ha la bomba inseriamo una esplosione e aumentiamo 5 punti
            audioBomba.play();
            let statistiche=document.querySelectorAll("section p span");
            punteggio+=5;   
            statistiche[1].innerText=punteggio;
            buchi[num].removeChild(strumento);
            effEsplosione.style.width=widthTalpa;
            buchi[num].appendChild(effEsplosione);
            window.setTimeout(e=>{buchi[num].removeChild(effEsplosione);},325);
        }
    }
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
        intervalloTalpa=window.setInterval(posizionaTalpa, 1000/difficolta);
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
        if(!(audioMartello.paused)){
            audioMartello.currentTime=0;
        }
        audioMartello.play();
        // pos 0=time 1=punti
        let statistiche=document.querySelectorAll("section p span");
        let strumento=document.getElementById("strumento");
        let padre=event.target.parentElement;
        //se la talpa si trova nel box dello strumento evitiamo di aggiungere un punto se colpita
        if(strumento==null || (padreStrumento=strumento.parentElement)!=padre){
            punteggio+=1;
            statistiche[1].innerText=punteggio;
            padre.removeChild(imageTalpa);
            let talpaColpita=document.createElement("img");
            talpaColpita.style.width=widthTalpa;
            talpaColpita.src="./src/images/talpaColpita.png";
            talpaColpita.setAttribute("draggable", "false");
            padre.appendChild(talpaColpita)
            window.setTimeout(e=>{
                if(padre.contains(talpaColpita)){
                    padre.removeChild(talpaColpita)
                }
            }, 500);
        }
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
        if(secondi===20){
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
 * abbassiamo il martello per 150mS
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
    img.setAttribute("draggable", "false");
    img.setAttribute("onclick", "rimuoviStrumentoClick(event)");
    img.style.width=widthTalpa;
    img.style.zIndex=100;
    img.id="strumento";
    switch (document.body.style.cursor.toString()){
        case 'url("./src/cursors/bomba.cur"), auto': 
            img.setAttribute("src", "./src/images/bomba.png");
            div.appendChild(img);
            changeButtonStatus(true);
            break;
        case 'url("./src/cursors/lucchetto.cur"), auto': 
            img.setAttribute("src", "./src/images/lucchetto.png");
            div.appendChild(img);
            changeButtonStatus(true);
            break;
        case 'url("./src/cursors/martello.cur"), auto': 
            img.setAttribute("src", "./src/images/martello.png");
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
function updateVolume(){
    volume=document.getElementById("volume").value;
    document.getElementById("valueVolume").innerText=volume;
    audioMartello.volume=volume/100;
    audioBomba.volume=volume/100;
}
function updateDifficolta(){
    difficolta=document.getElementById("difficolà").value;
    document.getElementById("valueDifficoltà").innerText=difficolta;
    if(statoGioco){
        alert("Per cambiare la difficoltà devi fermare il gioco e riavviarlo");
    }
}