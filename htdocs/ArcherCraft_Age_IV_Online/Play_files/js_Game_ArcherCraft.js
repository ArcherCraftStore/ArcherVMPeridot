var healer =function(){
Player.inventory.FAK.qty=Player.inventory.FAK.qty-1;
Player.health.current+=Player.inventory.FAK.heal;
};
function die(errormsg){
  alert(errormsg);
}
document.addEventListener("DOMContentLoaded", function() {
 var idbSupported = false;

if("indexedDB" in window) {
  idbSupported = true;
}else{
 throw  alert("Error!");
}
if(idbSupported){
  alert("IndexedDB Supported! Good!");
  console.log("Opening A Database for " + Player.name);
  var openRequest = indexedDB.open("Players",1);
  openRequest.onupgradeneeded = function(e) {
            console.log("Upgrading...");
            var thisDB = e.target.result;
            
             if(!thisDB.objectStoreNames.contains("PlayersOS")) {
                thisDB.createObjectStore("PlayersOS");
                
            }
        };
 
        openRequest.onsuccess = function(e) {
            console.log("Success!");
            db = e.target.result;
        };
 
        openRequest.onerror = function(e) {
            console.log("Error");
            console.dir(e);
        };
}

var transaction = db.transaction(["Players"],"readwrite");
    var store = transaction.objectStore("PlayersOS");
    var request = store.add(Player, 1);
    request.onerror = function(e) {
        console.log("Error",e.target.error.name);
        //some type of error handler
    };
 
    request.onsuccess = function(e) {
        console.log("Woot! Did it");
    };
},false);
$(document).ready(function(){
try{
 wsh.exec({
        code: function() {
            //TODO: Replace `echo` by `apis.tts`
            apis.tts('So, you wanna be \n a king, eh?');
        },
        process: function(data, meta) {
            $('body').html(meta.view);
        }
    });
alert("So, you wanna be \n a king, eh?")
   
alert("Welcome to the right place for that\n, ArcherCraft!");
alert("Congratulations! You are now a squire!");
var Player={
name:prompt("Name?:"),
title:"squire",
health:{
max:100,
current:100,
},
inventory:{
FAK:{
heal:50,
qty:20
}
},
level:1,
attack:10,
};
var Enemy={
name:"Militia",
attack:10,
health:{
max:100,
current:100,
}
};
localStorage.name=Player.name;
var slaying=true;
var Enemy_health=Enemy.health.current;
var health=Player.health.current;
console.time("Play Timer");
while(slaying){
    var Action=window.prompt("attack, or flee?");
    if(Action==="attack"){
var youHit=Math.random();
Action=null;
}
else if(Action==="flee"){
     alert("The" +Enemy.name + "kills you! You're toast!");
console.timeEnd("Play Timer");
      slaying = false;
      break;
}else{
Action=prompt('Try Again!');
}
    if (youHit>0.50) {
alert("You hit the"+ Enemy.name +" and did "+ Player.attack + "damage!");
Enemy_health=Enemy_health-Player.attack;
    }
if (Enemy_health===0){
alert("You did it! You slew the" + Enemy.name+ "!");
console.timeEnd("Play Timer");
lose=false;
 slaying = false;
      }
        else if(health===0){
             alert("The" +Enemy.name + "kills you! You're toast.");
             lose=true;
console.timeEnd("Play Timer");
slaying = false;

}
  else if(youHit<0.50){
      alert("you miss!");
      health=health-Enemy.attack;
      alert(health);
}}


if(lose){
  alert('Game Over');
  var redirect= confirm('Exit the game? ');
  if(redirect){
  window.location.assign('index.php');
  }
}else{
 alert('Winner!');
 alert('You Earned a Badge!');
 $('td#DSBadge').append('<img src="_static/media/img/DSBadge.png">');
alert("You need first aid.");
if(confirm("Apply?")){
Player.inventory.FAK.qty=Player.inventory.FAK.qty-1;
Player.health.current+=Player.inventory.FAK.heal;
alert('you earned a badge!');
alert('level up!');
Player.level+=1;
Player.health.max=Player.level*100;
Player.health.current=Player.health.max;
Player.attack+=10;
localStorage.Player=JSON.stringify(Player);
var idbSupported = false;

if("indexedDB" in window) {
  idbSupported = true;
}else{
 throw  alert("Error!");
}
if(idbSupported){
  alert("IndexedDB Supported! Good!");
  console.log("Opening A Database for " + Player.name);
  var openRequest = indexedDB.open("Players",1);
  openRequest.onupgradeneeded = function(e) {
            console.log("Upgrading...");
            var thisDB = e.target.result;
            
             if(!thisDB.objectStoreNames.contains("PlayersOS")) {
                thisDB.createObjectStore("PlayersOS");
                
            }
        };
 
        openRequest.onsuccess = function(e) {
            console.log("Success!");
            db = e.target.result;
        };
 
        openRequest.onerror = function(e) {
            console.log("Error");
            console.dir(e);
        };
}

var transaction = db.transaction(["Players"],"readwrite");
    db.createObjectStore("PlayersOS", {savedData: localStorage.player});
}
Enemy.health.current=100;
Enemy.name="Sentinelese MilitiaMan";
Enemy.attack=20;
Player.arms={
crossbow_w_sword:{
attack:20
}
};
alert('Oh no!Here comes a Sentinelese MilitiaMan');

var slaying=true;
var Enemy_health=Enemy.health.current;
var health=Player.health.current;
while(slaying){
    var Action=prompt("attack, or flee?");
    switch(Action){
    case "attack":
      if(attack===Player.attack){
var youHit=Math.random();
}else if(attack===Player.arms.crossbow_w_sword.attack){
  Player.arms.crossbow_w_sword.ammo += -1;
  var youHit=Math.random();
}
break;
case "flee":
     alert("You lose!");
      slaying = false;
      break;
default:
 Action=prompt("attack, or flee?");
break;
}
    if (youHit>0.50) {
alert("You hit the"+ Enemy.name+" and did "+ Player.arms.crossbow_w_sword.attack + "damage!");
Enemy_health=Enemy_health-Player.arms.crossbow_w_sword.attack;
    }
if (Enemy_health<=0){
alert("You win!");
lose=false;
 slaying = false;
      }
        else if(health<=0){
             alert("You lose!");
             lose=true;
slaying = false;
}
  else if(youHit<0.50){
      alert("you miss!");
      health=health-Enemy.attack;
      alert(health);
}}
}
if(lose)
{
  alert('Game Over');
  window.location.assign('index.php');
}else{
  

attack+=10;
var slaying=true;
var Enemy_health=100;
var health=Player.health.current;
var attack;
while(slaying){
    var Action=prompt("attack, or flee?");
    switch(Action){
    case "attack":
var youHit=Math.random();
break;
case "flee":
     alert("You lose!");
      slaying = false;
      break;
      case "changeweapon":
      var weapon=prompt('crossbow or sword?');
      if(weapon==="sword"){
      attack=Player.attack;
      }else if(weapon==="crossbow"){
      attack=Player.arms.crossbow_w_sword.attack;
      }
      youHit=Math.random();
      break;
case "heal":
healer();
break;
case "craft":
  var craftexp = /craft\s/;
  result = Action.search(craftexp);
  
  if(result !== null){
    switch(Action.substring(6,12)){
      case "medpack":
           var medpackrand = Math.floor(Math.random()*2+1);
           if(medpackrand===2){
            var medpackcount = Math.floor(Math.random()*3+1)*10;
            Player.inventory.FAK.qty+=medpackcount;
           }
        break;
        
    }
  }
  break;
default:
 Action=prompt("attack, or flee?");
break;
}
    if (youHit>0.50) {
alert("You hit the"+ Enemy.name+" and did "+ attack + "damage!");
Enemy_health=Enemy_health-attack;
    }
  if(Enemy_health===0){
  alert("You win!");
   lose=false;
     slaying = false;
      } else if(health<=0){
             alert("You lose!");
             lose=true;
slaying = false;
window.location.assign('index.htm');
}
  else if(youHit<0.50){
      alert("you miss!");
      health=health-Enemy.attack;
      alert(health);
}}}
if(lose){
  alert('game over');
  window.location.assign('index.php');
}else{
alert("You just Leveled Up!!!!");
Player.level=2;
Player.health.max=Player.health.max*2;
Player.attack=Player.attack*2;
document.write('<embed src="images/badges/svg/b4-henson-weldon-intro.svg" type="image/svg+xml"/>');


alert('Off to China!');
alert('From an AP World History Textbook:');
alert('On the eastern edge of the vast Eurasian landmass,\n Neolithic cultures developed as early as \n 8000 B.C.E.\n A more complex civilization evolved in the second\n and first millennia B.C.E. Under the Shang\n and Zhou dynasties, many of the elements \n of classical Chinese civilization emerged and spread\n across East Asia. As in Mesopotamia,\n Egypt, and the Indus Valley, the rise \n of cities, specialization of labor,\n bureaucratic government, \n writing, and other advanced technologies depended \n on the exploitation of a\n great river system�the Yellow River\n (Huang He [hwahng- HUH]) and its\n tributaries�to support intensive agriculture.');
alert('1:Nature');
alert('China is isolated by formidable natural barriers:\n the Himalaya (him-uh-LAY-uh) mountain range on the southwest;\n the Pamir (pah-MEER) and Tian Mountains and the Takla Makan\n (TAH-kluh muh-KAHN) Desert on the west; and the Gobi (GO-bee) Desert and the treeless,\n grassy hills and plains of the Mongolian steppe to the northwest and north (see Map 2.1). To the\n east lies the Pacific Ocean. Although China�s separation was not total�trade goods, people, and\n ideas moved back and forth between China, India, and Central Asia�in many respects its development\n was distinctive.');
alert('The Dao is just like an immortal being,\n just like the other gods');
alert('to win, you must destroy 30 Chinese warriors. Good Luck.');
    for(var i=1; i<31; i++){
    var slaying=true;
var Enemy_health=Enemy.health.current;
var health=Player.health.current;
console.time("Play Timer");
while(slaying){
    var Action=prompt("attack, or flee?");
    if(Action==="attack"){
var youHit=Math.random();
Action=null;
}
else if(Action==="flee"){
     alert("The" +Enemy.name + "kills you! You're toast!");
console.timeEnd("Play Timer");
      slaying = false;
      break;
}else{
Action=prompt('Try Again!');
}
    if (youHit>0.50) {
alert("You hit the"+ Enemy.name +" and did "+ Player.attack + "damage!");
Enemy_health=Enemy_health-Player.attack;
    }
if (Enemy_health===0){
alert("You did it! You slew the" + Enemy.name+ "!");
console.timeEnd("Play Timer");
lose=false;
 slaying = false;
      }
        else if(health===0){
             alert("The" +Enemy.name + "kills you! You're toast.");
             lose=true;
console.timeEnd("Play Timer");
slaying = false;
break;
}
  else if(youHit<0.50){
      alert("you miss!");
      health=health-Enemy.attack;
      alert(health);
}}

 if(lose=true){
break;
}
 localStorage.Player = JSON.stringify(Player);
}
}
}catch(error){
alert('Error 001:Code is not Working.');
var leave=confirm('Do You Want to Exit to google?');
if(leave){
window.location.assign('http://www.google.com');
}
}
});