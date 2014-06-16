function Arbalest(name,ammo,attack){
	this.attack=attack;
	this.name=name;
	this.ammo=ammo;
	this.getFiringPower=function(){
		return this.attack;
	};
}
function EnemyAct(){
   
}
var Player={};
Player.name=prompt('Name:');
Player.class="Nobility";
Player.level=16;
Player.health=new Object();
Player.health.max=1600;
Player.health.current=Player.health.max;
Player.attack=160;
Player.exp=1600;
Player.title="count";
Player.inventory=new Object();
Player.inventory.FAK=new Object();
Player.inventory.FAK.heal= new Object();
Player.inventory.FAK.qty=300;
Player.arms=new Object();
Player.arms.arbalest= new Array();
for(var i=1; i<6; i++){
Player.arms.arbalest[i]=new Arbalest(i,i*10,i*20);
}
Player.arms.cannon=new Object();
Player.arms.cannon.attack=Player.attack-50;
Player.arms.cannon.ammo=120;
Player.arms.cannon.name="EX1 Hand Cannon";
Player.arms.longbow=new Object();
with(Player.arms.longbow){
this.attack=150;
this.ammo=160;
this.name=prompt("Name your Longbow:");
}
Player.arms={
crossbow_w_sword:{
attack:130
}
};
Player.arms.BOWZI=new Object();
Player.arms.BOWZI.attack=50;
Player.arms.BOWZI.ammo=1000;
Player.arms.BOWZI.name=prompt('Name your BOWZI:');
var Enemy={
name:"Militia",
attack:10,
health:{
max:1600,
current:1600,
},
arms:{
  arbalest: new Arbalest('ARBY1',200,80)
}
};
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
        if(Player.arms.crossbow_w_sword.ammo>0){
      attack=Player.arms.crossbow_w_sword.attack;
        }else{
          alert('You are out of ammo!');
        }
      }else if(weapon==="BOWZI"){
        
        attack=Player.arms.BOWZI.attack;
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
if(youKill >= KILLED){
alert("Enemy Killed With Ballista");
Enemy_health=0;
slaying=false;
}else if(youKill < KILLED){
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
    }
if(health===0){
             alert(" The" +Enemy.name + "kills you! You're toast.");
             lose=true;
slaying = false;
}
  else if(youHit<0.50){
      alert("you miss!");
      EHit=Math.floor(Math.random()*2+1);
      if(EHit===1){
          alert('an stream of silvers summons red!');
          health+=-(Enemy.attack);
      }
      else if(EHit===2){
        alert('A stream of tan shoot pain into your very hand');
        health=health-Enemy.arms.arbalest.attack;
        Enemy.arms.arbalest.ammo+=-1;
      }
      alert(health);
}
}
if(lose){
  alert('You were killed tumultuously in battle. Game Over');
  window.location.assign('index.php');
}else{
  alert('Level up');
  Player.level+=1;
  Player.health.current+=100;
  Player.health.max+=100;
  Player.attack+=10;
  alert('The American Revolution was inspired by the Enlightenment , and the colonists actually win.');
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
        if(Player.arms.crossbow_w_sword.ammo>0){
      attack=Player.arms.crossbow_w_sword.attack;
        }else{
          alert('You are out of ammo!');
        }
      }else if(weapon==="BOWZI"){
        
        attack=Player.arms.BOWZI.attack;
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
if(youKill >= KILLED){
alert("Enemy Killed With Ballista");
Enemy_health=0;
slaying=false;
}else if(youKill < KILLED){
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
    }
if(health===0){
             alert(" The" +Enemy.name + "kills you! You're toast.");
             lose=true;
slaying = false;
}
  else if(youHit<0.50){
      alert("you miss!");
      EHit=Math.floor(Math.random()*2+1);
      if(EHit===1){
          alert('an stream of silvers summons red!');
          health+=-(Enemy.attack);
      }
      else if(EHit===2){
        alert('A stream of tan shoot pain into your very hand');
        health=health-Enemy.arms.arbalest.attack;
        Enemy.arms.arbalest.ammo+=-1;
      }
      alert(health);
}
}
}
if(lose){
  alert('You were killed tumultuously in battle. Game Over');
  window.location.assign('index.php');
}else{
  alert('Level up');
  Player.level+=1;
  Player.health.current+=100;
  Player.health.max+=100;
  Player.attack+=10;
  alert('The American Revolution was inspired by the Enlightenment , and the colonists actually win.');
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
        if(Player.arms.crossbow_w_sword.ammo>0){
      attack=Player.arms.crossbow_w_sword.attack;
        }else{
          alert('You are out of ammo!');
        }
      }else if(weapon==="BOWZI"){
        
        attack=Player.arms.BOWZI.attack;
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
if(youKill >= KILLED){
alert("Enemy Killed With Ballista");
Enemy_health=0;
slaying=false;
}else if(youKill < KILLED){
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
    }
if(health===0){
             alert(" The" +Enemy.name + "kills you! You're toast.");
             lose=true;
slaying = false;
}
  else if(youHit<0.50){
      alert("you miss!");
      EHit=Math.floor(Math.random()*2+1);
      if(EHit===1){
          alert('an stream of silvers summons red!');
          health+=-(Enemy.attack);
      }
      else if(EHit===2){
        alert('A stream of tan shoot pain into your very hand');
        health=health-Enemy.arms.arbalest.attack;
        Enemy.arms.arbalest.ammo+=-1;
      }
      alert(health);
}
}
}
if(lose){
  alert('You were killed tumultuously in battle. Game Over');
  window.location.assign('index.php');
}else{
  alert('Level up');
  Player.level+=1;
  Player.health.current+=100;
  Player.health.max+=100;
  Player.attack+=10;
  Player.title="marquis";
}