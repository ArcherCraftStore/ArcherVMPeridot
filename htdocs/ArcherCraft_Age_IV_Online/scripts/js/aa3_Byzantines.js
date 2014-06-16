
try{
console.clear();
var Player=JSON.parse(GetCookie(prompt('Username:')));
var Enemy={
name:"Militia",
attack:10,
health:{
max:400,
current:400
}
};
console.log("Entering  Medieval Civilization....");
console.group("History Report:The Franks");
alert("Charlemagne:King of the franks , and emperor of the Holy Roman Empire during the 800s.");
console.log("Charlemagne:King of the franks , and emperor of the Holy Roman Empire during the 800s.");
alert("Charlemagne's coronation symbolized a new beggining for the western half of europe.");
console.log("Charlemagne's coronation symbolized a new beggining for the western half of europe.");
console.groupEnd();
console.info(Player.name + " has entered the game.");
for(var i=1;i<101;i++){
 if(i%2===0){
var slaying=true;
var Enemy_health=Enemy.health.current;
var health=Player.health.current;
while(slaying===true){
    var Action=prompt("attack, or flee?");
if (Enemy_health===0){              
alert("You did it! You slew the" + Enemy.name+ "!");             
lose=false;          
 slaying = false;   
      } else if(Enemy_health >0){
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
Player.inventory.FAK.qty=Player.inventory.FAK.qty-1;
health+=Player.inventory.FAK.heal;
alert(Player.health.current);
Action=prompt("attack, or flee?");
break;
case "ninja_attack":
attack=Player.arms.crossbow_w_sword.attack;
youHit=Math.random();
attack= Player.attack;
youHit=Math.random();
break;
case "ballista":
var youKill=Math.random();
if(youKill >= 0.5){
alert("Enemy Killed With Ballista");
Enemy_health=0;
slaying=false;
}else if(youKill < 0.5){
alert("You missed!");
Action=prompt("Try Again!");
}
break;
default:   
 Action=prompt("attack, or flee?");
break;
}
    if (youHit>0.50) {      
alert("You hit the"+ Enemy.name +" and did "+ Player.attack + "damage!");     
Enemy_health=Enemy_health-Player.attack;
if(Enemy_health===0){
  alert("You are Victorious!");
  lose=false;
  slaying=false;
  Player.exp+=10;
}
else if(health===0){
             alert("The" +Enemy.name + "kills you! You're toast.");
             lose=true;          
slaying = false; 
break;
}}
  else if(youHit<0.50){
      alert("you miss!");
      health=health-Enemy.attack;
      alert(health);  
}}  
}
}else if(i%2!==0){
  var slaying = true;
while(slaying===true){
var youHit=Math.floor(Math.random()*1+0);
var damageThisRound=Math.floor(Math.random()*6+1);
var totalDamage=0;
if(youHit===1){
console.log("You are Victorious!");
totalDamage += damageThisRound;
win=true;
Player.exp+=10;
var slaying = false;
}else if(youHit===0){
    console.log("You have been defeated!");
    lose=true;
    slaying=false;
}
}
}
}
alert("The Army of Magnicople has congratulated you...");
Player.arms.arbalest= new Array();
console.log("Creating Array..");
Player.userAge=parseInt(prompt("Age:"), 10);
if(isNaN(userAge)){
   throw "Does not compute";
}else if(userAge>18){
for(var i=1; i<(userAge-17)+1; i++){
  Player.arms.arbalest[i]=new Arbalest("ARBY-001",100,90);
}
}else{
  for(var i=1; i<Player.userAge+1; i++){
    Player.arms.arbalest[i]=new Arbalest("ARBY-001",100,90);
}
}
for(var i=1;i<101;i++){
 if(i%2===0){
var slaying=true;
var Enemy_health=Enemy.health.current;
var health=Player.health.current;
while(slaying===true){
    var Action=prompt("attack, or flee?");
if (Enemy_health===0){              
alert("You did it! You slew the" + Enemy.name+ "!");             
lose=false;          
 slaying = false;   
      } else if(Enemy_health >0){
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
      }else if(weapon===arbalest){
        var choice_w=parseInt(prompt("Choose an Arbalest:"),10);
        attack=Player.arms.arbalest[choice_w].attack;
      }
      youHit=Math.random();
      break;
case "heal":
Player.inventory.FAK.qty=Player.inventory.FAK.qty-1;
health+=Player.inventory.FAK.heal;
alert(Player.health.current);
Action=prompt("attack, or flee?");
break;
case "ninja_attack":
attack=Player.arms.crossbow_w_sword.attack;
youHit=Math.random();
attack= Player.attack;
youHit=Math.random();
break;
case "ballista":
var youKill=Math.random();
if(youKill >= 0.5){
alert("Enemy Killed With Ballista");
Enemy_health=0;
slaying=false;
}else if(youKill < 0.5){
alert("You missed!");
Action=prompt("Try Again!");
}
break;
default:   
 Action=prompt("attack, or flee?");
break;
}
    if (youHit>0.50) {      
alert("You hit the"+ Enemy.name +" and did "+ Player.attack + "damage!");     
Enemy_health=Enemy_health-Player.attack;
if(Enemy_health===0){
  alert("You are Victorious!");
  lose=false;
  slaying=false;
  Player.exp+=10;
}
else if(health===0){
             alert("The" +Enemy.name + "kills you! You're toast.");
             lose=true;          
slaying = false; 
break;
}}
  else if(youHit<0.50){
      alert("you miss!");
      health=health-Enemy.attack;
      alert(health);  
}}  
if(i===100){
  alert("Level Up!");
  Player.level+=1;
Player.health.max+=100;
Player.health.current=max;
Player.attack+=10;
Player.exp+=100;
}
}
}else if(i%2!==0){
  var slaying = true;
while(slaying===true){
var youHit=Math.floor(Math.random()*1+0);
var damageThisRound=Math.floor(Math.random()*6+1);
var totalDamage=0;
if(youHit===1){
console.log("You are Victorious!");
totalDamage += damageThisRound;
win=true;
Player.exp+=10;
var slaying = false;
}else if(youHit===0){
    console.log("You have been defeated!");
    lose=true;
    slaying=false;
}
}
}
}
if(!lose){
alert("You Were victorious");
alert('However..');
alert("lords and popes lost power after the crusades.");
alert("You escape the Black Plague.");
alert("Congrats! you're now a noble!");
Player.class="Nobility";
Player.level+=1;
Player.health.max+=100;
Player.health.current=max;
Player.attack+=10;
Player.exp+=100;
Player.title="baron";
setCookie(Player.name,JSON.stringify(Player),500);
window.location.assign('AgeIV.htm');
}else{
  alert("You Died in battle.game over");
  window.location.assign("index.htm");
}
}catch(err){
  alert(err.message);
}