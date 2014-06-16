// JavaScript Document
var popUpWin=0;
function popUpWindow(URLStr, left, top, width, height)
{
  if(popUpWin)
  {
    if(!popUpWin.closed) popUpWin.close();
  }
  popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
}

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
alert('Welcome to Age IV Renaissance Edition.');
alert('Western Europe emerged from the medieval ages, during an era known as the Renaissance.');
alert('The Discovery of Chinese gunpowder led to the development of cannons.');

var slaying=true;
var Enemy_health=Enemy.health.current;
var health=Player.health.current;
console.time("Play Timer");
popUpWindow('start battle.html', 400, 400, 300, 1200);
while(slaying){
    var Action=prompt("attack, or flee?");
    if(Action==="attack"){
var youHit=Math.random();
Player.arms.cannon.ammo+=-1;
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
Enemy_health=Enemy_health-Player.arms.cannon.attack;
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
}
this.exp+=100;
}
alert("The invention of the longbow allowed soldier to shoot accurately up to 300 yards which decreased the importance of knights on horseback");
Player.arms.longbow=new Object();
with(Player.arms.longbow){
  Player.attack=140;
Player.ammo=150;
Player.name=prompt("Name your Longbow:");
}
var slaying=true;
var Enemy_health=Enemy.health.current;
var health=Player.health.current;
console.time("Play Timer");
popUpWindow('start battle.html', 400, 400, 300, 1200);
while(slaying){
    var Action=prompt("attack, or flee?");
    if(Action==="attack"){
var youHit=Math.random();
Player.arms.cannon.ammo+=-1;
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
Enemy_health=Enemy_health-Player.arms.longbow.attack;
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
}}if(!lose){
alert("Level up!");
Player.attack+=10;
Player.level+=1;

Player.max+=100;
Player.health.current=Player.health.max;

Player.exp+=100;
Player.title="viscount";
}
alert('congrats! You are now a Viscount!');  