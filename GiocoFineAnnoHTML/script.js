
let statoGioco=false;
function changeGameState(){
    let bPause=document.getElementById("pauseButton");
    let bStatus=document.querySelector("nav button");
    if(!statoGioco){
        bPause.style.display="inline";
        bStatus.innerText="Stop";
        statoGioco=true;
    }else{
        bPause.style.display="none";
        bStatus.innerText="Start";
        statoGioco=false;
    }
}

function selectItems(elemento){
    console.log("url('./"+elemento+"'), auto")
    document.body.style.cursor = "url('./"+elemento+"'), auto";
}