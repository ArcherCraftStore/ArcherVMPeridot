var Player=new Object();
Player.name=prompt("Name?:"),
Player.title="squire";
Player.health=new Object();
Player.health.max=1200;
Player.inventory=new Object();
Player.inventory.FAK=new Object();
Player.inventory.FAK.heal= new Object();
Player.inventory.FAK.qty=300;
Player.level=1;
Player.attack=150;
Player.arms={
crossbow_w_sword:{
attack:20
}
};
Player.class="Nobility";
Player.level=15;
Player.health.max=1500;
Player.health.current=Player.health.max;
Player.attack
Player.exp=1500;
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
with(Player.arms.longbow){
this.attack=140;
this.ammo=150;
this.name=prompt("Name your Longbow:");
}
alert('Welcome to Age V.");
alert("During the middle ages, the Catholic Religion was the dominant religion.")
var slaying=true;
var Enemy_health=Enemy.health.current;
var health=Player.health.current;
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
while(slaying===true){
    var Action=prompt("attack, or flee?");
if (Enemy_health===0){              
alert("You did it! You slew the" + Enemy.name+ "!");             
lose=false