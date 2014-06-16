var KILLED = .50
try{
var Player={
name:prompt("Name?:"),
title:"squire",
health:{
max:100,
current:100
},
inventory:{
FAK:{
heal:50,
qty:20
}
},
level:1,
attack:10
};
var Enemy={
name:"Militia",
attack:10,
health:{
max:100,
current:100
}
};
Player.arms={
crossbow_w_sword:{
attack:20
}
};
for(var i=200;i<201;i-=1){
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
if(youKill >= KILLED){
alert("Enemy Killed With Ballista")
Enemy_health=0;
slaying=false;
}else if(youKill < KILLED){
alert("You missed!");
Action=prompt("Try Again!");
}
break;
default:   
 youHit=.49;
break;
}}
    if (youHit>0.50) {      
alert("You hit the"+ Enemy.name +" and did "+ Player.attack + "damage!");     
Enemy_health=Enemy_health-Player.attack;
    }     
if(health===0){
             alert("The" +Enemy.name + "kills you! You're toast.");
             lose=true;          
slaying = false; 
break;
}
  else if(youHit<0.50){
      alert("you miss!");
      health=health-Enemy.attack;
      alert(health);  
}}  if(i<200){
    Enemy_health=100;
}else if(i=0){
    alert('You Win!');
localStorage.Player=JSON.stringify(Player);
break;
}else if(i>0 && health===0){
alert("You Lose!")
break;
}
}
}catch(error){
alert("Error%s Detected!",error);
}finally{
alert('Fixed');
}
