(() => {
'use strict';
const state={tabs:[{id:crypto.randomUUID(),name:'Venta 1',items:[]}],active:0,scale:null,lastWeight:null};
const $=s=>document.querySelector(s);
function safeNumber(v){const n=Number(v);return Number.isFinite(n)?n:0}
function evalQty(expr){
 if(!/^[0-9+\-*/().\s]+$/.test(expr)) throw new Error('Expresión inválida');
 const n=Function('"use strict";return ('+expr+')')();
 if(!Number.isFinite(n)||n<=0) throw new Error('Cantidad inválida');
 return Math.round(n*10000)/10000;
}
async function connectScale(){
 if(!('serial' in navigator)){alert('Esta báscula requiere Vendi Bridge o un navegador compatible.');return;}
 const port=await navigator.serial.requestPort(); await port.open({baudRate:9600});
 state.scale=port; $('#scaleStatus').textContent='⚖ Báscula conectada';
 const decoder=new TextDecoderStream(); port.readable.pipeTo(decoder.writable).catch(()=>{});
 const reader=decoder.readable.getReader(); let buffer='';
 while(true){const {value,done}=await reader.read();if(done)break;buffer+=value;
  const lines=buffer.split(/\r?\n/);buffer=lines.pop()||'';
  for(const line of lines){const m=line.replace(',','.').match(/(-?\d+(?:\.\d+)?)/);if(m){const w=safeNumber(m[1]);if(w>=0){state.lastWeight=w;$('#qty').value=String(w);$('#scaleWeight').textContent=w.toFixed(3)+' kg';}}}
 }
}
function newTab(){state.tabs.push({id:crypto.randomUUID(),name:'Venta '+(state.tabs.length+1),items:[]});state.active=state.tabs.length-1;renderTabs();}
function renderTabs(){const box=$('#saleTabs');box.innerHTML='';state.tabs.forEach((t,i)=>{const b=document.createElement('button');b.textContent=t.name;b.className=i===state.active?'selected':'';b.onclick=()=>{state.active=i;renderTabs();};box.appendChild(b)});const add=document.createElement('button');add.textContent='＋ Nueva venta';add.onclick=newTab;box.appendChild(add);}
window.VendiPOS={evalQty,connectScale,newTab};
document.addEventListener('DOMContentLoaded',()=>{renderTabs();$('#connectScale')?.addEventListener('click',()=>connectScale().catch(e=>alert('No se pudo conectar: '+e.message)));$('#qtyCalc')?.addEventListener('click',()=>{try{$('#qty').value=evalQty($('#qty').value)}catch(e){alert(e.message)}});});
})();