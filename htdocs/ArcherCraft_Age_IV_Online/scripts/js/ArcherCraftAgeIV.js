function Arbalest(name,ammo,attack){
	this.attack=attack;
	this.name=name;
	this.ammo=ammo;
	this.getFiringPower=function(){
		return this.attack;
	};
}
 try{
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
Player.inventory.FAK.qty=20;
Player.level=1;
Player.attack=110;
Player.arms={
crossbow_w_sword:{
attack:20
}
};
Player.class="Nobility";
Player.level+=1;
Player.health.max+=100;
Player.health.current=Player.health.max;
Player.attack+=10;
Player.exp+=100;
Player.title="baron";
Player.arms.arbalest= new Array();
for(var i=1; i<6; i++){
Player.arms.arbalest[i]=new Arbalest(i,i*10,i*20);
}


alert('Welcome to the nobility of ArcherCraft.');
alert('You are in Mesoamerica..');
alert('While classical civilizations were developing in the Mediterranean & Asia..');
alert('advanced societies were developing in isolation in the Americas  ');
alert('During the Ice Age, prehistoric nomads migrated across a land bridge between Asia and America');
alert('During the Neolithic Revolution, these nomads settled across a land bridge between Asia and America');
alert('The first American civilization was the Olmec civilization ');
alert('You are about to battle the Enemies');
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
alert('While the Olmecs were in decline around 400 BCE, the Mayans were evolving & borrowed many Olmec Ideas');
alert('you are about to now fight your way out of sacrifice');
localStorage.Player=JSON.stringify(Player);
window.location.assign('AgeIVPartII.htm');
}catch(err){}