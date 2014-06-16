
function Arbalest(name,ammo,attack){
	this.attack=attack;
	this.name=name;
	this.ammo=ammo;
	this.getFiringPower=function(){
		return this.attack;
	};
}
 var Enemy ={
name:"Militia",
attack:10,
health:{
max:400,
current:400
}
};
var Player=new Object();
Player.name=prompt("Name?:"),
Player.title="squire";
Player.health=new Object();
Player.health.max=1600;
Player.inventory=new Object();
Player.inventory.FAK=new Object();
Player.inventory.FAK.heal= new Object();
Player.inventory.FAK.qty=300;
Player.level=1;
Player.attack=160;
Player.arms={
crossbow_w_sword:{
attack:20
}
};
Player.class="Nobility";
Player.level=16;
Player.health.max=1600;
Player.health.current=Player.health.max;
Player.attack=160;
Player.exp=1600;
Player.title="baron";
Player.arms.arbalest= new Array();
for(var i=1; i<6; i++){
Player.arms.arbalest[i]=new Arbalest(i,i*10,i*20);
}
Player.arms.cannon=new Object();
Player.arms.cannon.attack=Player.attack-50;
Player.arms.cannon.ammo=120;
Player.arms.cannon.name="EX1 Hand Cannon";
Player.arms.longbow=new Object();
  Player.arms.longbow.attack=140;
Player.arms.longbow.ammo=150;
Player.arms.longbow.name=prompt("Name your Longbow:");


alert('During the Middle Ages, the Catholic religion was the the Dominant Religion in Western Europe.');
alert('Due to Indulgences, Martin Luther Stated the Protestant Reformation by being excommunicated from the Catholic Church at the Diet of Worms.');
alert('Mission: Destroy the Anti-Protestant forces of The Anti-Delosians!');
console.log('Loading the Battle Stats...');
console.timeStamp('timestamping Battle...');
   var slaying=true;
var Enemy_health=Enemy.health.current;
var health=Player.health.current;
while(slaying===true){
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
      }else if(weapon===arbalest){
        var choice_w=parseInt(prompt("Choose an Arbalest:"),10);
        attack=Player.arms.arbalest[choice_w].attack;
      }else if(weapon==="cannon"){
        attack=Player.arms.cannon.attack;
      }else if(weapon==="longbow"){
        attack=Player.arms.longbow.attack;
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
with(Player){
this.attack+=10;
this.level+=1;
with(this.health){
this.max+=100;
this.current=this.max;
}
this.exp+=100;
}

alert('From the 1400s to the 1700s, Europe Experienced an "Age of Exploration".');
alert('It also invoved cruelty.');
alert('Mission: Stop Cortes and his men from taking over the Aztecs!');
for(var i=1;i<5;i++){
   var slaying=true;
var Enemy_health=Enemy.health.current;
var health=Player.health.current;
var ApplyArmor=confirm('Apply Armor?');
if(ApplyArmor){
  var applied=true;
  Player.defense=50;
}else{
  applied=false;
}
while(slaying===true){
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
      }else if(weapon===arbalest){
        var choice_w=parseInt(prompt("Choose an Arbalest:"),10);
        attack=Player.arms.arbalest[choice_w].attack;
      }else if(weapon==="cannon"){
        attack=Player.arms.cannon.attack;
      }else if(weapon==="longbow"){
        attack=Player.arms.longbow.attack;
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
with(Player){
this.attack+=10;
this.level+=1;
with(this.health){
this.max+=100;
this.current=this.max;
}
this.exp+=100;
}
alert('By the End of the Renaissance, New Nations were formed.');
alert('Mission: Stop the anti-Absolutists for killing the King of Delos!');
for(var i=1;i<5;i++){
   var slaying=true;
var Enemy_health=Enemy.health.current;
var health=Player.health.current;
while(slaying===true){
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
      }else if(weapon===arbalest){
        var choice_w=parseInt(prompt("Choose an Arbalest:"),10);
        attack=Player.arms.arbalest[choice_w].attack;
      }else if(weapon==="cannon"){
        attack=Player.arms.cannon.attack;
      }else if(weapon==="longbow"){
        attack=Player.arms.longbow.attack;
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
      if(!applied){
      health=health-Enemy.attack;
  }else{
          health=health-(Enemy.attack-Player.defense);
          alert('You defended yourself!');
  }
      alert(health);
}}
}
with(Player){
this.attack+=10;
this.level+=1;
with(this.health){
this.max+=100;
this.current=this.max;
}
this.exp+=100;
}
var slaying=true;
var Enemy_health=Enemy.health.current;
var health=Player.health.current;
while(slaying){
  var Action=prompt('Attack or Flee:');
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
 Action=prompt("attack or flee?");
 break;
 }  if (youHit>0.50) {
alert("You hit the"+ Enemy.name +" and did "+ Player.attack + "damage!");
Enemy_health=Enemy_health-Player.attack;
    }
if (Enemy_health<=0){
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
window.location.assign('index.htm');
}
  else if(youHit<0.50){
      alert("you miss!");
      health=health-Enemy.attack;
      alert(health);
}}
alert("Level up!");
with(Player){
this.attack+=10;
this.level+=1;
with(this.health){
this.max+=100;
this.current=this.max;
}}